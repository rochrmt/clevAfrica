<?php
require __DIR__ . '/inc.php';
$data = load_content();

page_header('Séminaires & formations', 'Formations CLEV Africa Consulting : séminaires pour dirigeants, managers et équipes — en salle ou en intra-entreprise.');
?>

    <!-- PAGE HERO -->
    <section class="page-hero section--dark">
      <div class="container">
        <p class="eyebrow" data-reveal><span class="eyebrow__dot"></span>Séminaires &amp; formations</p>
        <h1 class="title" data-split>Renforcer les <em>compétences</em> de vos équipes.</h1>
        <p class="section__lead" data-reveal data-delay="0.15">
          Des séminaires destinés aux dirigeants, managers et futurs managers, animés en salle de conférence ou en intra-entreprise. Programmes proposés par le cabinet ou construits sur mesure selon vos besoins.
        </p>
      </div>
    </section>

    <!-- FORMATIONS -->
    <section class="section">
      <div class="container">
        <div class="formations-grid">
          <?php foreach ($data['formations'] as $f): ?>
            <article class="formation" id="<?= e($f['id']) ?>" data-reveal>
              <?php if (!empty($f['image'])): ?>
                <div class="formation__media"><img src="<?= e($f['image']) ?>" alt="<?= e($f['title']) ?>" loading="lazy"></div>
              <?php endif; ?>
              <div class="formation__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M12 3L1 8l11 5 9-4.09V15h2V8L12 3zM5 11.5V16c0 1.66 3.13 3 7 3s7-1.34 7-3v-4.5l-7 3.19-7-3.19z"/></svg>
              </div>
              <h2><?= e($f['title']) ?></h2>
              <?php if (!empty($f['meta'])): ?>
                <p class="formation__meta"><?= e($f['meta']) ?></p>
              <?php endif; ?>
              <p><?= e($f['description']) ?></p>
              <a class="btn btn--primary btn--sm formation__cta" href="<?= e(contact_url(
                  'Management & formation',
                  "Bonjour, je souhaite m'inscrire à la formation « {$f['title']} ». Merci de me recontacter."
              )) ?>">
                <span>S'inscrire à cette formation</span>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </a>
            </article>
          <?php endforeach; ?>
        </div>

        <div class="cta-band" data-reveal>
          <h2 class="title">Une formation <em>sur mesure</em> ?</h2>
          <p>Nous construisons des programmes adaptés à votre contexte : fiscaux, juridiques, sociaux, douaniers ou managériaux. Demandez le catalogue complet ou décrivez votre besoin.</p>
          <div class="cta-band__actions">
            <a href="index.php#contact" class="btn btn--primary">
              <span>Demander le catalogue</span>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
            <a href="expertises.php" class="btn btn--dark">
              <span>Voir nos domaines d'expertise</span>
            </a>
          </div>
        </div>
      </div>
    </section>

<?php page_footer(); ?>
