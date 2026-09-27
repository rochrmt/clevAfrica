<?php
/* =========================================================
   CLEV Africa Consulting — Helpers partagés
   ========================================================= */

const DATA_FILE = __DIR__ . '/data/content.json';
const ADMIN_PASSWORD_FILE = __DIR__ . '/data/.admin_password';

function load_content(): array {
    $json = is_file(DATA_FILE) ? file_get_contents(DATA_FILE) : '';
    $data = json_decode($json, true);
    if (!is_array($data)) $data = [];
    return array_merge(['expertises' => [], 'abonnements' => [], 'formations' => []], $data);
}

function save_content(array $data): void {
    $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE;
    $json = json_encode($data, $flags);
    if ($json === false) return; // ne jamais écraser le fichier avec un encodage raté
    if (is_file(DATA_FILE)) @copy(DATA_FILE, DATA_FILE . '.bak');
    file_put_contents(DATA_FILE, $json, LOCK_EX);
}

function e(?string $s): string {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

// Répare les entrées non-UTF-8 (ex. Windows-1252) avant stockage
function to_utf8(string $s): string {
    if ($s === '' || preg_match('//u', $s)) return $s;
    $t = @iconv('Windows-1252', 'UTF-8', $s);
    return $t !== false ? $t : $s;
}

function slugify(string $s): string {
    if (function_exists('iconv')) {
        $t = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
        if ($t !== false) $s = $t;
    }
    $s = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $s));
    return trim($s, '-') ?: 'item-' . substr(md5(random_bytes(8)), 0, 6);
}

/* ---------- Images du site ---------- */
function site_image(array $siteImg, string $key, string $default = ''): string {
    $v = $siteImg[$key] ?? '';
    return (is_string($v) && $v !== '') ? $v : $default;
}

const UPLOAD_DIR = __DIR__ . '/uploads';
const UPLOAD_EXT = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
const UPLOAD_MAX = 8 * 1024 * 1024; // 8 Mo

// Retourne le chemin relatif "uploads/xxx.ext" ou null si pas d'upload valide
function handle_upload(array $file): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
    if (($file['size'] ?? 0) <= 0 || $file['size'] > UPLOAD_MAX) return null;
    $ext = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, UPLOAD_EXT, true)) return null;
    if (class_exists('finfo')) {
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!is_string($mime) || !str_starts_with($mime, 'image/')) return null;
    }
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0775, true);
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . '/' . $name)) return null;
    return 'uploads/' . $name;
}

// Supprime le fichier uniquement s'il vit dans uploads/ (jamais les assets d'origine)
function delete_upload(?string $path): void {
    if (!is_string($path) || !str_starts_with($path, 'uploads/')) return;
    $full = __DIR__ . '/' . $path;
    if (is_file($full)) unlink($full);
}

/* ---------- Pré-remplissage du formulaire de contact ---------- */
const CONTACT_DOMAINES = [
    'Droit des affaires',
    'Fiscalité',
    'Social',
    'Douane',
    'Réglementation des changes',
    'Prix de transfert',
    'Management & formation',
    'Autre',
];

// Lien vers le formulaire de contact pré-rempli (index.php?domaine=…&sujet=…#contact)
function contact_url(string $domaine, string $sujet): string {
    return 'index.php?' . http_build_query(['domaine' => $domaine, 'sujet' => $sujet]) . '#contact';
}

// Correspondance expertise → option du champ « Domaine concerné »
function expertise_domaine_option(string $expertiseId): string {
    $map = [
        'fiscalite'            => 'Fiscalité',
        'juridique'            => 'Droit des affaires',
        'gouvernance'          => 'Management & formation',
        'social-paie'          => 'Social',
        'douane-changes'       => 'Douane',
        'prix-transfert'       => 'Prix de transfert',
        'ressources-humaines'  => 'Social',
    ];
    return $map[$expertiseId] ?? 'Autre';
}

/* ---------- Auth admin ---------- */
function admin_password(): string {
    // Priorité : variable d'environnement, puis fichier data/.admin_password, sinon défaut
    $env = getenv('ADMIN_PASSWORD');
    if ($env) return $env;
    if (is_file(ADMIN_PASSWORD_FILE)) {
        $p = trim((string) file_get_contents(ADMIN_PASSWORD_FILE));
        if ($p !== '') return $p;
    }
    return 'clevadmin';
}

function is_admin(): bool {
    return !empty($_SESSION['admin']);
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}

function check_csrf(): void {
    if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
        http_response_code(403);
        exit('Requête invalide (CSRF).');
    }
}

/* ---------- Partials ---------- */
function page_header(string $title, string $description): void {
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title><?= e($title) ?> — CLEV Africa Consulting</title>
  <meta name="description" content="<?= e($description) ?>">
  <meta name="theme-color" content="#0f3d2a">
  <link rel="icon" type="image/png" href="assets/logo/clev-africa.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@1,9..144,300;1,9..144,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

  <div class="cursor" id="cursor" aria-hidden="true">
    <div class="cursor__dot"></div>
    <div class="cursor__ring"></div>
    <div class="cursor__label"></div>
  </div>

  <div class="progress" id="progress" aria-hidden="true"><span></span></div>

  <header class="header is-scrolled" id="header">
    <div class="container header__inner">
      <a href="index.php" class="brand" aria-label="CLEV Africa Consulting — Accueil">
        <img src="assets/logo/clev-africa.png" class="brand__logo brand__logo--main" alt="CLEV Africa Consulting">
        <img src="assets/logo/clev-africa-white.png" class="brand__logo brand__logo--alt" alt="" aria-hidden="true">
      </a>

      <nav class="nav" id="nav" aria-label="Navigation principale">
        <ul class="nav__list">
          <li><a href="index.php#a-propos" class="nav__link" data-magnetic>À propos</a></li>
          <li><a href="expertises.php" class="nav__link" data-magnetic>Expertises</a></li>
          <li><a href="index.php#approche" class="nav__link" data-magnetic>Approche</a></li>
          <li><a href="formations.php" class="nav__link" data-magnetic>Formations</a></li>
          <li><a href="index.php#references" class="nav__link" data-magnetic>Références</a></li>
          <li><a href="index.php#contact" class="nav__link" data-magnetic>Contact</a></li>
        </ul>
        <a href="index.php#contact" class="btn btn--primary btn--sm nav__cta" data-magnetic>
          <span>Parler à un expert</span>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </nav>

      <button class="burger" id="burger" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="nav">
        <span></span><span></span>
      </button>
    </div>
  </header>

  <main id="main">
    <?php
}

function page_footer(): void {
    ?>
  </main>

  <footer class="footer">
    <div class="container footer__inner">
      <div class="footer__brand">
        <img src="assets/logo/clev-africa-white.png" class="brand__logo brand__logo--light" alt="CLEV Africa Consulting">
        <p>Cabinet de conseil juridique, fiscal, social, douanier et en management. Douala · Bangui · Afrique.</p>
        <a class="footer__social" href="https://www.linkedin.com/company/clevafricaconsulting/" target="_blank" rel="noopener" aria-label="LinkedIn CLEV Africa Consulting" data-magnetic>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9v9M6 5v.5M10 18v-9M10 13c0-2.5 1.5-4 4-4s4 1.5 4 4v5"/></svg>
          <span>LinkedIn</span>
        </a>
      </div>
      <nav class="footer__col" aria-label="Plan du site">
        <h4>Navigation</h4>
        <a href="index.php#a-propos">À propos</a>
        <a href="expertises.php">Expertises</a>
        <a href="index.php#approche">Approche</a>
        <a href="formations.php">Formations</a>
        <a href="index.php#references">Références</a>
        <a href="index.php#contact">Contact</a>
      </nav>
      <div class="footer__col">
        <h4>Expertises</h4>
        <a href="expertises.php#juridique">Juridique</a>
        <a href="expertises.php#fiscalite">Fiscalité</a>
        <a href="expertises.php#social-paie">Social &amp; paie</a>
        <a href="expertises.php#douane-changes">Douane &amp; changes</a>
        <a href="expertises.php#prix-transfert">Prix de transfert</a>
        <a href="expertises.php#ressources-humaines">Ressources humaines</a>
      </div>
      <div class="footer__col">
        <h4>Contact</h4>
        <a href="mailto:contact@clevafricaconsulting.com">contact@clevafricaconsulting.com</a>
        <a href="tel:+23676184343">+236 76 18 43 43</a>
        <a href="tel:+237675217369">+237 675 21 73 69</a>
        <address>Avenue Boganda, Lakouanga<br>BP 2250 Bangui — RCA</address>
      </div>
    </div>
    <div class="container footer__bottom">
      <span>© <span id="year"></span> CLEV Africa Consulting. Tous droits réservés.</span>
      <span class="footer__values">Responsabilité · Excellence · Intégrité · Flexibilité</span>
    </div>
  </footer>

  <button class="totop" id="totop" aria-label="Retour en haut">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
  </button>

  <script src="js/main.js" defer></script>
</body>
</html>
    <?php
}
