<?php
session_start();
require __DIR__ . '/inc.php';

const COLLECTIONS = ['expertises', 'formations', 'abonnements'];
const COLLECTION_LABELS = [
    'expertises'  => 'Expertises',
    'formations'  => 'Formations',
    'abonnements' => 'Abonnements',
];
const SITE_IMAGE_SLOTS = [
    'hero'   => 'Image du hero (accueil)',
    'about'  => 'Image « À propos » (accueil)',
    'poster' => 'Affiche séminaire (accueil)',
];

/* ---------- Déconnexion ---------- */
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

/* ---------- Connexion ---------- */
$loginError = '';
if (isset($_POST['login_password'])) {
    if (hash_equals(admin_password(), (string) $_POST['login_password'])) {
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit;
    }
    $loginError = 'Mot de passe incorrect.';
}

if (!is_admin()) {
    render_login($loginError);
    exit;
}

/* ---------- Helpers métier ---------- */
function sanitize_item(string $col, array $p): array {
    $t = fn($k) => to_utf8(trim((string) ($p[$k] ?? '')));
    switch ($col) {
        case 'expertises':
            return [
                'title'    => $t('title'),
                'summary'  => $t('summary'),
                'services' => array_values(array_filter(array_map(fn($l) => to_utf8(trim($l)), preg_split('/\r?\n/', (string) ($p['services'] ?? ''))))),
            ];
        case 'formations':
            return [
                'title'       => $t('title'),
                'meta'        => $t('meta'),
                'description' => $t('description'),
            ];
        case 'abonnements':
            return [
                'name'        => $t('name'),
                'price'       => $t('price'),
                'description' => $t('description'),
            ];
    }
    return [];
}

function item_label(string $col, array $item): string {
    return $item['title'] ?? $item['name'] ?? '(sans titre)';
}

function unique_id(array $items, string $base): string {
    $ids = array_column($items, 'id');
    $id = $base;
    $i = 2;
    while (in_array($id, $ids, true)) $id = $base . '-' . $i++;
    return $id;
}

function render_image_field(array $it): void {
    ?>
          <div class="field">
            <span>Image</span>
            <?php if (!empty($it['image'])): ?>
              <div class="current-img">
                <img src="<?= e($it['image']) ?>" alt="">
                <label class="check"><input type="checkbox" name="delete_image" value="1"> Supprimer l'image actuelle</label>
              </div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
            <small class="hint">JPG, PNG, WebP ou SVG — 8 Mo max.</small>
          </div>
    <?php
}

/* ---------- Actions POST (CRUD + images) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    check_csrf();
    $data = load_content();
    $action = (string) $_POST['action'];
    $col = (string) ($_POST['collection'] ?? '');
    $tab = in_array($col, COLLECTIONS, true) ? $col : 'images';

    if ($action === 'save' && in_array($col, COLLECTIONS, true)) {
        $item = sanitize_item($col, $_POST);
        $id = trim((string) ($_POST['id'] ?? ''));
        $old = null;
        if ($id !== '') {
            foreach ($data[$col] as $it) {
                if (($it['id'] ?? '') === $id) { $old = $it; break; }
            }
        }
        // Image de l'élément (expertises & formations)
        if (in_array($col, ['expertises', 'formations'], true)) {
            $newImg = handle_upload($_FILES['image'] ?? []);
            if ($newImg !== null) {
                delete_upload($old['image'] ?? null);
                $item['image'] = $newImg;
            } elseif (!empty($_POST['delete_image'])) {
                delete_upload($old['image'] ?? null);
                $item['image'] = '';
            } else {
                $item['image'] = $old['image'] ?? '';
            }
        }
        if ($old !== null) {
            $item['id'] = $id;
            foreach ($data[$col] as $i => $it) {
                if (($it['id'] ?? '') === $id) { $data[$col][$i] = $item; break; }
            }
            $_SESSION['flash'] = '« ' . item_label($col, $item) . ' » mis à jour.';
        } else {
            $base = $item['title'] ?? $item['name'] ?? 'item';
            $item['id'] = $id !== '' ? $id : unique_id($data[$col], slugify($base));
            $data[$col][] = $item;
            $_SESSION['flash'] = '« ' . item_label($col, $item) . ' » ajouté.';
        }
        save_content($data);
    } elseif ($action === 'delete' && in_array($col, COLLECTIONS, true)) {
        $id = (string) ($_POST['id'] ?? '');
        foreach ($data[$col] as $it) {
            if (($it['id'] ?? '') === $id) { delete_upload($it['image'] ?? null); break; }
        }
        $data[$col] = array_values(array_filter($data[$col], fn($it) => ($it['id'] ?? '') !== $id));
        save_content($data);
        $_SESSION['flash'] = 'Élément supprimé.';
    } elseif ($action === 'site_image') {
        $key = (string) ($_POST['key'] ?? '');
        if (in_array($key, ['hero', 'about', 'poster'], true)) {
            $img = handle_upload($_FILES['image'] ?? []);
            if ($img !== null) {
                delete_upload($data['site_images'][$key] ?? null);
                $data['site_images'][$key] = $img;
                save_content($data);
                $_SESSION['flash'] = 'Image mise à jour.';
            } else {
                $_SESSION['flash_err'] = 'Envoi impossible : image invalide ou trop lourde (max 8 Mo).';
            }
        }
    } elseif ($action === 'site_image_delete') {
        $key = (string) ($_POST['key'] ?? '');
        if (in_array($key, ['hero', 'about', 'poster'], true)) {
            delete_upload($data['site_images'][$key] ?? null);
            $data['site_images'][$key] = '';
            save_content($data);
            $_SESSION['flash'] = 'Image supprimée.';
        }
    } elseif ($action === 'gallery_add') {
        $img = handle_upload($_FILES['image'] ?? []);
        if ($img !== null) {
            $data['site_images']['gallery'][] = [
                'src'     => $img,
                'caption' => to_utf8(trim((string) ($_POST['caption'] ?? ''))),
                'wide'    => !empty($_POST['wide']),
            ];
            save_content($data);
            $_SESSION['flash'] = 'Image ajoutée à la galerie.';
        } else {
            $_SESSION['flash_err'] = 'Envoi impossible : image invalide ou trop lourde (max 8 Mo).';
        }
    } elseif ($action === 'gallery_delete') {
        $idx = (int) ($_POST['idx'] ?? -1);
        if (isset($data['site_images']['gallery'][$idx])) {
            delete_upload($data['site_images']['gallery'][$idx]['src'] ?? null);
            array_splice($data['site_images']['gallery'], $idx, 1);
            save_content($data);
            $_SESSION['flash'] = 'Image retirée de la galerie.';
        }
    }
    header('Location: admin.php?tab=' . urlencode($tab));
    exit;
}

/* ---------- Rendu ---------- */
$collection = (string) ($_GET['tab'] ?? 'expertises');
$isImagesTab = $collection === 'images';
if (!$isImagesTab && !in_array($collection, COLLECTIONS, true)) $collection = 'expertises';
$data = load_content();
$items = $isImagesTab ? [] : $data[$collection];

$editItem = null;
if (isset($_GET['edit']) && !$isImagesTab) {
    foreach ($items as $it) {
        if (($it['id'] ?? '') === $_GET['edit']) { $editItem = $it; break; }
    }
}
$isNew = isset($_GET['new']) && !$isImagesTab;

$flash = $_SESSION['flash'] ?? null;
$flashErr = $_SESSION['flash_err'] ?? null;
unset($_SESSION['flash'], $_SESSION['flash_err']);


function render_login(string $error): void {
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administration — CLEV Africa Consulting</title>
  <meta name="robots" content="noindex">
  <link rel="icon" type="image/png" href="assets/logo/clev-africa.png">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body class="login-body">
  <main class="login-card">
    <img src="assets/logo/clev-africa.png" class="login-logo" alt="CLEV Africa Consulting">
    <h1>Administration</h1>
    <p>Connectez-vous pour gérer les expertises, formations et abonnements du site.</p>
    <?php if ($error !== ''): ?><p class="alert alert--error"><?= e($error) ?></p><?php endif; ?>
    <form method="post" action="admin.php">
      <label class="field">
        <span>Mot de passe</span>
        <input type="password" name="login_password" required autofocus autocomplete="current-password">
      </label>
      <button type="submit" class="btn-primary">Se connecter</button>
    </form>
    <a class="back-link" href="index.php">← Retour au site</a>
  </main>
</body>
</html>
    <?php
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administration — CLEV Africa Consulting</title>
  <meta name="robots" content="noindex">
  <link rel="icon" type="image/png" href="assets/logo/clev-africa.png">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<header class="topbar">
  <a href="index.php" class="topbar__brand">Clev<strong>Africa</strong> <small>Admin</small></a>
  <nav class="topbar__nav">
    <a href="expertises.php" target="_blank">Voir le site</a>
    <a href="admin.php?logout=1">Déconnexion</a>
  </nav>
</header>

<main class="admin">
  <?php if ($flash): ?><p class="alert alert--ok"><?= e($flash) ?></p><?php endif; ?>
  <?php if ($flashErr): ?><p class="alert alert--error"><?= e($flashErr) ?></p><?php endif; ?>

  <nav class="tabs">
    <?php foreach (COLLECTION_LABELS as $key => $label): ?>
      <a href="admin.php?tab=<?= $key ?>" class="tab<?= $collection === $key ? ' is-active' : '' ?>">
        <?= e($label) ?> <span class="tab__count"><?= count($data[$key]) ?></span>
      </a>
    <?php endforeach; ?>
    <a href="admin.php?tab=images" class="tab<?= $isImagesTab ? ' is-active' : '' ?>">
      Images du site <span class="tab__count"><?= count($data['site_images']['gallery'] ?? []) ?></span>
    </a>
  </nav>

  <?php if ($isImagesTab): ?>
  <div class="panel">
    <div class="panel__head">
      <h1>Images du site</h1>
    </div>

    <section class="media-section">
      <h2>Sections principales</h2>
      <div class="media-slots">
        <?php foreach (SITE_IMAGE_SLOTS as $key => $label): $src = site_image($data['site_images'] ?? [], $key); ?>
          <div class="media-slot">
            <div class="media-slot__thumb">
              <?php if ($src !== ''): ?>
                <img src="<?= e($src) ?>" alt="<?= e($label) ?>">
              <?php else: ?>
                <span>Aucune image</span>
              <?php endif; ?>
            </div>
            <h3><?= e($label) ?></h3>
            <form method="post" action="admin.php" enctype="multipart/form-data" class="media-slot__form">
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="action" value="site_image">
              <input type="hidden" name="key" value="<?= e($key) ?>">
              <input type="file" name="image" accept="image/*" required>
              <button type="submit" class="btn-mini">Remplacer</button>
            </form>
            <?php if ($src !== ''): ?>
            <form method="post" action="admin.php" onsubmit="return confirm('Supprimer cette image ?');">
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="action" value="site_image_delete">
              <input type="hidden" name="key" value="<?= e($key) ?>">
              <button type="submit" class="btn-mini btn-mini--danger">Supprimer</button>
            </form>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="media-section">
      <h2>Galerie photos (accueil)</h2>
      <div class="media-grid">
        <?php foreach (($data['site_images']['gallery'] ?? []) as $i => $shot): ?>
          <figure class="media-thumb">
            <img src="<?= e($shot['src']) ?>" alt="<?= e($shot['caption'] ?? '') ?>">
            <figcaption><?= e($shot['caption'] ?? '') ?><?= !empty($shot['wide']) ? ' · large' : '' ?></figcaption>
            <form method="post" action="admin.php" onsubmit="return confirm('Retirer cette image de la galerie ?');">
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="action" value="gallery_delete">
              <input type="hidden" name="idx" value="<?= $i ?>">
              <button type="submit" class="btn-mini btn-mini--danger">Supprimer</button>
            </form>
          </figure>
        <?php endforeach; ?>
      </div>
      <form method="post" action="admin.php" enctype="multipart/form-data" class="media-add">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="action" value="gallery_add">
        <label class="field"><span>Nouvelle image</span><input type="file" name="image" accept="image/*" required></label>
        <label class="field"><span>Légende</span><input type="text" name="caption" placeholder="Ex. Séminaire — Douala"></label>
        <label class="check"><input type="checkbox" name="wide" value="1"> Grand format (double largeur)</label>
        <button type="submit" class="btn-primary">Ajouter à la galerie</button>
      </form>
    </section>
  </div>
  <?php else: ?>
  <div class="panel">
    <div class="panel__head">
      <h1><?= e(COLLECTION_LABELS[$collection]) ?></h1>
      <a href="admin.php?tab=<?= $collection ?>&new=1" class="btn-primary">+ Nouveau</a>
    </div>

    <?php if ($isNew || $editItem): ?>
      <?php
        $it = $editItem ?? [];
        $id = $editItem['id'] ?? '';
      ?>
      <form class="edit-form" method="post" action="admin.php" enctype="multipart/form-data" accept-charset="UTF-8">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="collection" value="<?= e($collection) ?>">
        <input type="hidden" name="id" value="<?= e($id) ?>">
        <h2><?= $id ? 'Modifier' : 'Nouveau' ?> — <?= e(COLLECTION_LABELS[$collection]) ?></h2>

        <?php if ($collection === 'expertises'): ?>
          <label class="field"><span>Titre</span>
            <input type="text" name="title" required value="<?= e($it['title'] ?? '') ?>">
          </label>
          <label class="field"><span>Résumé (accroche sous le titre)</span>
            <textarea name="summary" rows="2"><?= e($it['summary'] ?? '') ?></textarea>
          </label>
          <label class="field"><span>Prestations (une par ligne)</span>
            <textarea name="services" rows="12"><?= e(implode("\n", $it['services'] ?? [])) ?></textarea>
          </label>
          <?php render_image_field($it); ?>
        <?php elseif ($collection === 'formations'): ?>
          <label class="field"><span>Titre</span>
            <input type="text" name="title" required value="<?= e($it['title'] ?? '') ?>">
          </label>
          <label class="field"><span>Méta (format, public, durée…)</span>
            <input type="text" name="meta" value="<?= e($it['meta'] ?? '') ?>" placeholder="Ex. Séminaire · 2 jours · Bangui">
          </label>
          <label class="field"><span>Description</span>
            <textarea name="description" rows="4"><?= e($it['description'] ?? '') ?></textarea>
          </label>
          <?php render_image_field($it); ?>
        <?php else: ?>
          <label class="field"><span>Nom de la formule</span>
            <input type="text" name="name" required value="<?= e($it['name'] ?? '') ?>" placeholder="Ex. OR">
          </label>
          <label class="field"><span>Prix</span>
            <input type="text" name="price" value="<?= e($it['price'] ?? '') ?>" placeholder="Ex. 2 500 000 FCFA / an">
          </label>
          <label class="field"><span>Description</span>
            <textarea name="description" rows="4"><?= e($it['description'] ?? '') ?></textarea>
          </label>
        <?php endif; ?>

        <div class="form-actions">
          <button type="submit" class="btn-primary">Enregistrer</button>
          <a href="admin.php?tab=<?= $collection ?>" class="btn-ghost">Annuler</a>
        </div>
      </form>
    <?php endif; ?>

    <ul class="item-list">
      <?php foreach ($items as $it): ?>
        <li class="item-row">
          <?php if (!empty($it['image'])): ?><img class="item-thumb" src="<?= e($it['image']) ?>" alt=""><?php endif; ?>
          <div class="item-row__main">
            <strong><?= e(item_label($collection, $it)) ?></strong>
            <small>
              <?php
                if ($collection === 'expertises') echo count($it['services'] ?? []) . ' prestation(s)';
                elseif ($collection === 'abonnements') echo e($it['price'] ?? '');
                else echo e($it['meta'] ?? '');
              ?>
            </small>
          </div>
          <div class="item-row__actions">
            <a class="btn-mini" href="admin.php?tab=<?= $collection ?>&edit=<?= urlencode($it['id'] ?? '') ?>">Modifier</a>
            <form method="post" action="admin.php" onsubmit="return confirm('Supprimer « <?= e(item_label($collection, $it)) ?> » ?');">
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="collection" value="<?= e($collection) ?>">
              <input type="hidden" name="id" value="<?= e($it['id'] ?? '') ?>">
              <button type="submit" class="btn-mini btn-mini--danger">Supprimer</button>
            </form>
          </div>
        </li>
      <?php endforeach; ?>
      <?php if (!$items): ?>
        <li class="item-empty">Aucun élément. Cliquez sur « + Nouveau » pour commencer.</li>
      <?php endif; ?>
    </ul>
  </div>
  <?php endif; ?>
</main>

</body>
</html>
