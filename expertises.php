<?php
require __DIR__ . '/inc.php';
$data = load_content();

page_header('Nos expertises', 'Expertises CLEV Africa Consulting : fiscalité, juridique, gouvernance, social et paie, douane, prix de transfert et ressources humaines.');
?>

    <!-- PAGE HERO -->
    <section class="page-hero section--dark">
      <div class="container">
        <p class="eyebrow" data-reveal><span class="eyebrow__dot"></span>Nos différents services</p>
        <h1 class="title" data-split>Nos domaines <em>d'expertise</em>.</h1>
        <p class="section__lead" data-reveal data-delay="0.15">
          Sept pôles d'expertise et des abonnements annuels pour accompagner votre entreprise partout en Afrique centrale et au-delà.
        </p>
      </div>
    </section>

    <!-- DOMAINES D'EXPERTISE -->
    <section class="section">
      <div class="container domains">
        <?php foreach ($data['expertises'] as $i => $exp): ?>
          <article class="domain<?= !empty($exp['image']) ? ' domain--img' : '' ?>" id="<?= e($exp['id']) ?>" data-reveal>
            <span class="domain__num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <div class="domain__body">
              <h2><?= e($exp['title']) ?></h2>
              <?php if (!empty($exp['summary'])): ?>
                <p class="domain__lead"><?= e($exp['summary']) ?></p>
              <?php endif; ?>
              <?php if (!empty($exp['services']) && is_array($exp['services'])): ?>
                <ul class="domain__list">
                  <?php foreach ($exp['services'] as $s): ?>
                    <li><?= e($s) ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              <a class="card__link" href="<?= e(contact_url(
                  expertise_domaine_option($exp['id'] ?? ''),
                  "Bonjour, je souhaite être accompagné(e) sur le domaine « {$exp['title']} ». Pouvez-vous me recontacter ?"
              )) ?>">Demander un devis <i>→</i></a>
            </div>
            <?php if (!empty($exp['image'])): ?>
              <div class="domain__media"><img src="<?= e($exp['image']) ?>" alt="<?= e($exp['title']) ?>" loading="lazy"></div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ABONNEMENTS -->
    <?php if (!empty($data['abonnements'])): ?>
    <section class="section section--sand">
      <div class="container">
        <div class="section__head section__head--center">
          <p class="eyebrow" data-reveal><span class="eyebrow__dot"></span>Abonnements annuels</p>
          <h2 class="title" data-split>Un accompagnement <em>continu</em>, toute l'année.</h2>
          <p class="section__lead" data-reveal data-delay="0.15">Trois formules d'accompagnement pour sécuriser votre conformité en permanence.</p>
        </div>
        <div class="plans">
          <?php foreach ($data['abonnements'] as $i => $plan): ?>
            <article class="plan<?= $i === 1 ? ' plan--featured' : '' ?>" data-reveal data-delay="<?= e((string) ($i * 0.12)) ?>">
              <h3><?= e($plan['name']) ?></h3>
              <p class="plan__price"><?= e($plan['price']) ?></p>
              <p><?= e($plan['description']) ?></p>
              <a href="<?= e(contact_url('Autre', "Bonjour, je souhaite souscrire à l'abonnement {$plan['name']} ({$plan['price']}). Merci de me recontacter.")) ?>" class="btn btn--dark btn--block"><span>Souscrire</span></a>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- CTA -->
    <section class="section">
      <div class="container cta-band" data-reveal>
        <h2 class="title">Un besoin <em>spécifique</em> ?</h2>
        <p>Parlez-nous de votre situation : un expert vous répond sous 48 heures.</p>
        <a href="index.php#contact" class="btn btn--primary">
          <span>Prendre rendez-vous</span>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </section>

<?php page_footer(); ?>
