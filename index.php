<?php
require __DIR__ . '/inc.php';
$data = load_content();
$siteImg = $data['site_images'] ?? [];
$gallery = $siteImg['gallery'] ?? [];

// Pré-remplissage du formulaire de contact via ?domaine=…&sujet=…
$prefDomaine = (string) ($_GET['domaine'] ?? '');
if (!in_array($prefDomaine, CONTACT_DOMAINES, true)) $prefDomaine = '';
$prefSujet = to_utf8(trim((string) ($_GET['sujet'] ?? '')));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>CLEV Africa Consulting — Conseil juridique, fiscal et management en Afrique</title>
  <meta name="description" content="CLEV Africa Consulting accompagne les entreprises en Afrique centrale : droit, fiscalité, social, douane, réglementation des changes, prix de transfert et management. Douala · Bangui.">
  <meta name="theme-color" content="#0f3d2a">
  <meta property="og:title" content="CLEV Africa Consulting">
  <meta property="og:description" content="Nous mettons notre expertise à votre service. Droit · Fiscalité · Social · Douane · Prix de transfert · Management.">
  <meta property="og:image" content="assets/img/team.jpg">
  <meta property="og:type" content="website">
  <link rel="icon" type="image/png" href="assets/logo/clev-africa.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@1,9..144,300;1,9..144,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

  <!-- Intro curtain -->
  <div class="intro" id="intro" aria-hidden="true">
    <img src="assets/logo/clev-africa-white.png" class="intro__logo-img" alt="CLEV Africa Consulting">
  </div>

  <!-- Custom cursor (desktop) -->
  <div class="cursor" id="cursor" aria-hidden="true">
    <div class="cursor__dot"></div>
    <div class="cursor__ring"></div>
    <div class="cursor__label"></div>
  </div>

  <!-- Scroll progress -->
  <div class="progress" id="progress" aria-hidden="true"><span></span></div>

  <!-- Header -->
  <header class="header" id="header">
    <div class="container header__inner">
      <a href="#accueil" class="brand" aria-label="CLEV Africa Consulting — Accueil" data-cursor="-hidden">
        <img src="assets/logo/clev-africa.png" class="brand__logo brand__logo--main" alt="CLEV Africa Consulting">
        <img src="assets/logo/clev-africa-white.png" class="brand__logo brand__logo--alt" alt="" aria-hidden="true">
      </a>

      <nav class="nav" id="nav" aria-label="Navigation principale">
        <ul class="nav__list">
          <li><a href="#a-propos" class="nav__link" data-magnetic>À propos</a></li>
          <li><a href="expertises.php" class="nav__link" data-magnetic>Expertises</a></li>
          <li><a href="#approche" class="nav__link" data-magnetic>Approche</a></li>
          <li><a href="formations.php" class="nav__link" data-magnetic>Formations</a></li>
          <li><a href="#references" class="nav__link" data-magnetic>Références</a></li>
          <li><a href="#contact" class="nav__link" data-magnetic>Contact</a></li>
        </ul>
        <a href="#contact" class="btn btn--primary btn--sm nav__cta" data-magnetic>
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

    <!-- HERO -->
    <section class="hero" id="accueil">
      <div class="hero__bg" data-parallax="0.25">
        <img src="<?= e(site_image($siteImg, 'hero', 'assets/img/team.jpg')) ?>" alt="L'équipe CLEV Africa Consulting et ses clients lors d'un séminaire à Bangui" fetchpriority="high">
        <div class="hero__veil"></div>
      </div>
      <div class="hero__grain" aria-hidden="true"></div>

      <div class="container hero__content">
        <p class="eyebrow hero__eyebrow" data-reveal>
          <span class="eyebrow__dot"></span>
          Cabinet de conseil · Douala &amp; Bangui
        </p>
        <h1 class="hero__title" data-split>
          L'expertise <em>juridique,</em> <em>fiscale</em> et <em>managériale</em> au service de vos ambitions en Afrique.
        </h1>
        <p class="hero__lead" data-reveal data-delay="0.6">
          CLEV Africa Consulting accompagne les entreprises et les institutions avec des solutions adaptées à leur situation&nbsp;: droit, fiscalité, social, douane, réglementation des changes, prix de transfert et management.
        </p>
        <div class="hero__actions" data-reveal data-delay="0.75">
          <a href="#expertises" class="btn btn--primary" data-magnetic>
            <span>Découvrir nos expertises</span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a href="#contact" class="btn btn--ghost" data-magnetic>
            <span>Prendre rendez-vous</span>
          </a>
        </div>
      </div>

      <div class="container hero__stats" data-reveal data-delay="0.9">
        <div class="stat">
          <span class="stat__num" data-count="2023">0</span>
          <span class="stat__label">Année de création</span>
        </div>
        <div class="stat">
          <span class="stat__num"><span data-count="2">0</span></span>
          <span class="stat__label">Pays — Cameroun &amp; RCA</span>
        </div>
        <div class="stat">
          <span class="stat__num"><span data-count="7">0</span></span>
          <span class="stat__label">Domaines d'expertise</span>
        </div>
        <div class="stat">
          <span class="stat__num"><span data-count="20">0</span>+</span>
          <span class="stat__label">Références qui nous font confiance</span>
        </div>
      </div>

      <a href="#confiance" class="hero__scroll" aria-label="Défiler">
        <span class="hero__mouse"><i></i></span>
        <span>Scroll</span>
      </a>
    </section>

    <!-- TRUST MARQUEE -->
    <section class="trust" id="confiance">
      <div class="container trust__head" data-reveal>
        <p class="eyebrow"><span class="eyebrow__dot"></span>Ils nous ont fait et continuent de nous faire confiance</p>
      </div>
      <div class="marquee" data-speed="45">
        <div class="marquee__track">
          <img src="assets/partners/ecobank.svg" alt="Ecobank">
          <img src="assets/partners/socacig.svg" alt="SOCACIG">
          <img src="assets/partners/bpmc.png" alt="Banque Populaire Maroco-Centrafricaine">
          <img src="assets/partners/mocaf.jpg" alt="MOCAF — Kota Doli">
          <img src="assets/partners/telecel.png" alt="Telecel RCA">
          <img src="assets/partners/mercure.png" alt="Mercure Logistics Centrafrique">
          <img src="assets/partners/bsic.png" alt="BSIC">
          <img src="assets/partners/cnss.jpg" alt="CNSS — Caisse Nationale de Sécurité Sociale">
          <img src="assets/partners/cfao.jpg" alt="CFAO Mobility">
          <img src="assets/partners/burval.svg" alt="Burval">
          <img src="assets/partners/onm.jpg" alt="Office National du Matériel">
          <img src="assets/partners/tradex.png" alt="Tradex">
          <img src="assets/partners/enerca.jpg" alt="ENERCA — Énergie Centrafricaine">
          <img src="assets/partners/sodeca.jpg" alt="Sodeca">
          <img src="assets/partners/pad.png" alt="Port Autonome de Douala">
          <img src="assets/partners/orange.svg" alt="Orange">
          <img src="assets/partners/agora.jpg" alt="Agora">
          <img src="assets/partners/bgfi.jpg" alt="BGFIBank">
          <img src="assets/partners/tamoil.svg" alt="Tamoil">
          <img src="assets/partners/sica.svg" alt="SICA Sécurité">
        </div>
      </div>
    </section>

    <!-- ABOUT -->
    <section class="about section" id="a-propos">
      <div class="container about__grid">
        <div class="about__media" data-reveal="mask">
          <figure class="tilt" data-tilt>
            <img src="<?= e(site_image($siteImg, 'about', 'assets/img/certificate.jpg')) ?>" alt="Remise d'attestation de participation à un séminaire CLEV Africa Consulting" loading="lazy">
            <figcaption>
              <span>Remise d'attestation</span>
              Séminaire de formation — Bangui
            </figcaption>
          </figure>
          <div class="about__badge" data-parallax="-0.08">
            <span>Depuis</span>
            <strong>2023</strong>
          </div>
        </div>

        <div class="about__text">
          <p class="eyebrow" data-reveal><span class="eyebrow__dot"></span>Qui sommes-nous</p>
          <h2 class="title" data-split>
            Un cabinet <em>panafricain</em>, ancré en Afrique centrale.
          </h2>
          <p data-reveal data-delay="0.1">
            Créé en 2023, CLEV Africa Consulting est un cabinet de conseil implanté à <strong>Douala (Cameroun)</strong> et <strong>Bangui (République Centrafricaine)</strong>. Nous mettons notre expertise au service de nos clients pour trouver des solutions adaptées à leur situation, et nous sommes en mesure de les accompagner partout en Afrique grâce à notre réseau de partenaires.
          </p>
          <p data-reveal data-delay="0.2">
            Notre ambition&nbsp;: devenir l'un des leaders du conseil juridique, fiscal et en management sur le continent, en alliant rigueur technique, connaissance fine des environnements locaux et proximité avec nos clients.
          </p>

          <ul class="values" data-reveal data-delay="0.3">
            <li class="value" data-spot>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l8 4v5c0 5-3.5 8.5-8 9-4.5-.5-8-4-8-9V7l8-4z"/><path d="M9 12l2 2 4-4"/></svg>
              <strong>Responsabilité</strong>
              <span>Nous assumons chaque recommandation.</span>
            </li>
            <li class="value" data-spot>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l2.6 5.6L20.5 9l-4.3 4 1.1 6L12 16.2 6.7 19l1.1-6L3.5 9l5.9-1.4z"/></svg>
              <strong>Excellence</strong>
              <span>Un niveau d'exigence sans compromis.</span>
            </li>
            <li class="value" data-spot>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v18M4 7h16M6 7l-3 6h6l-3-6zM18 7l-3 6h6l-3-6z"/></svg>
              <strong>Intégrité</strong>
              <span>Transparence et indépendance.</span>
            </li>
            <li class="value" data-spot>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 14c4-8 12-8 16 0M4 10c4 8 12 8 16 0"/></svg>
              <strong>Flexibilité</strong>
              <span>Des solutions sur mesure, réactives.</span>
            </li>
          </ul>
        </div>
      </div>
    </section>

    <!-- EXPERTISES -->
    <section class="expertises section section--dark" id="expertises">
      <div class="container">
        <div class="section__head">
          <p class="eyebrow" data-reveal><span class="eyebrow__dot"></span>Nos expertises</p>
          <h2 class="title" data-split>Sept domaines, <em>une seule</em> exigence.</h2>
          <p class="section__lead" data-reveal data-delay="0.15">
            Nous couvrons l'ensemble des problématiques réglementaires et de gestion auxquelles font face les entreprises opérant en zone CEMAC et au-delà.
          </p>
        </div>

        <div class="bento">
          <article class="card card--wide" data-spot data-reveal>
            <span class="card__index">01</span>
            <div class="card__icon">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v18M5 21h14M3 8l4-4 4 4M13 8l4-4 4 4M3 8c0 3 2 5 4 5s4-2 4-5M13 8c0 3 2 5 4 5s4-2 4-5"/></svg>
            </div>
            <h3>Droit des affaires</h3>
            <p>Constitution et restructuration de sociétés, secrétariat juridique, contrats, conformité OHADA, contentieux et accompagnement au quotidien de vos organes de gouvernance.</p>
            <a href="expertises.php#juridique" class="card__link">En savoir plus <i>→</i></a>
          </article>

          <article class="card" data-spot data-reveal data-delay="0.08">
            <span class="card__index">02</span>
            <div class="card__icon">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 5L5 19M7.5 9a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM16.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
            </div>
            <h3>Fiscalité</h3>
            <p>Optimisation et sécurisation fiscale, revues, déclarations, assistance lors des contrôles et recours contentieux.</p>
            <a href="expertises.php#fiscalite" class="card__link">En savoir plus <i>→</i></a>
          </article>

          <article class="card" data-spot data-reveal data-delay="0.16">
            <span class="card__index">03</span>
            <div class="card__icon">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 21c0-4 3.5-6 8-6s8 2 8 6"/></svg>
            </div>
            <h3>Social</h3>
            <p>Droit du travail, paie et déclarations CNSS, audits sociaux, gestion des relations avec l'administration du travail.</p>
            <a href="expertises.php#social-paie" class="card__link">En savoir plus <i>→</i></a>
          </article>

          <article class="card" data-spot data-reveal data-delay="0.24">
            <span class="card__index">04</span>
            <div class="card__icon">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9l9-5 9 5v10l-9 5-9-5V9zM3 9l9 5 9-5M12 14v10"/></svg>
            </div>
            <h3>Douane</h3>
            <p>Régimes douaniers, classement tarifaire, valeur en douane, litiges et optimisation de la chaîne import-export.</p>
            <a href="expertises.php#douane-changes" class="card__link">En savoir plus <i>→</i></a>
          </article>

          <article class="card" data-spot data-reveal data-delay="0.32">
            <span class="card__index">05</span>
            <div class="card__icon">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h13l-3-3M20 17H7l3 3M4 7v0M20 17v0"/></svg>
            </div>
            <h3>Réglementation des changes</h3>
            <p>Conformité à la réglementation des changes CEMAC (BEAC), domiciliation, rapatriement, dossiers et autorisations.</p>
            <a href="expertises.php#douane-changes" class="card__link">En savoir plus <i>→</i></a>
          </article>

          <article class="card" data-spot data-reveal data-delay="0.4">
            <span class="card__index">06</span>
            <div class="card__icon">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 17l6-6 4 4 8-8M14 7h7v7"/></svg>
            </div>
            <h3>Prix de transfert</h3>
            <p>Politique de prix de transfert, documentation (fichier local, fichier principal), analyses de comparabilité et défense en contrôle.</p>
            <a href="expertises.php#prix-transfert" class="card__link">En savoir plus <i>→</i></a>
          </article>

          <article class="card card--wide card--accent" data-spot data-reveal data-delay="0.48">
            <span class="card__index">07</span>
            <div class="card__icon">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 20h18M5 20V10l7-6 7 6v10M9 20v-6h6v6"/></svg>
            </div>
            <h3>Management &amp; organisation</h3>
            <p>Mise à niveau du contrôle interne, procédures, gouvernance, formation des managers et accompagnement du changement pour des organisations plus performantes.</p>
            <a href="expertises.php#gouvernance" class="card__link">En savoir plus <i>→</i></a>
          </article>
        </div>
      </div>
    </section>

    <!-- APPROCHE -->
    <section class="approach section" id="approche">
      <div class="container">
        <div class="section__head section__head--center">
          <p class="eyebrow" data-reveal><span class="eyebrow__dot"></span>Notre approche</p>
          <h2 class="title" data-split>Une méthode <em>claire</em>, des résultats concrets.</h2>
        </div>

        <ol class="steps" data-reveal>
          <li class="step">
            <span class="step__num">01</span>
            <h3>Écoute</h3>
            <p>Nous prenons le temps de comprendre votre activité, vos contraintes et vos objectifs.</p>
          </li>
          <li class="step">
            <span class="step__num">02</span>
            <h3>Diagnostic</h3>
            <p>Analyse rigoureuse de votre situation juridique, fiscale, sociale et organisationnelle.</p>
          </li>
          <li class="step">
            <span class="step__num">03</span>
            <h3>Solution</h3>
            <p>Des recommandations opérationnelles, adaptées à votre contexte et à vos moyens.</p>
          </li>
          <li class="step">
            <span class="step__num">04</span>
            <h3>Accompagnement</h3>
            <p>Mise en œuvre, suivi et formation de vos équipes pour des résultats durables.</p>
          </li>
        </ol>

        <blockquote class="quote" data-reveal>
          <p>« Nous mettons notre expertise à votre service&nbsp;: <em>être une solution</em> pour nos clients, partout en Afrique. »</p>
          <cite>— CLEV Africa Consulting</cite>
        </blockquote>
      </div>
    </section>

    <!-- FORMATIONS -->
    <section class="training section section--sand" id="formations">
      <div class="container">
        <div class="training__grid">
          <div class="training__text">
            <p class="eyebrow" data-reveal><span class="eyebrow__dot"></span>Séminaires &amp; formations</p>
            <h2 class="title" data-split>Renforcer les <em>compétences</em> de vos équipes.</h2>
            <p data-reveal data-delay="0.1">
              Nous animons des séminaires de formation destinés aux dirigeants, managers et futurs managers, en salle de conférence ou en intra-entreprise.
            </p>
            <ul class="training__list" data-reveal data-delay="0.2">
              <li>
                <strong>Rôle et responsabilité du manager au sein de l'entreprise</strong>
                <span>Affirmer son identité managériale, déployer des stratégies adaptatives et orienter la performance de son équipe.</span>
              </li>
              <li>
                <strong>Mettre à niveau le système de contrôle interne</strong>
                <span>Cartographie des risques, procédures, séparation des tâches et pilotage des objectifs.</span>
              </li>
              <li>
                <strong>Actualités fiscales, sociales et douanières</strong>
                <span>Décrypter les lois de finances et sécuriser vos obligations déclaratives.</span>
              </li>
            </ul>
            <a href="formations.php" class="btn btn--dark" data-reveal data-delay="0.3" data-magnetic>
              <span>Toutes nos formations</span>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
          </div>

          <div class="training__poster" data-reveal="mask">
            <figure class="tilt" data-tilt>
              <img src="<?= e(site_image($siteImg, 'poster', 'assets/img/seminar-poster.jpg')) ?>" alt="Affiche du séminaire de formation « Rôle et Responsabilité du Manager au sein de l'entreprise » — 19-20 juin 2025" loading="lazy">
            </figure>
          </div>
        </div>
      </div>

      <div class="gallery" id="gallery">
        <div class="container gallery__head">
          <p class="gallery__hint" data-reveal>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 12H3m0 0l3-3m-3 3l3 3M16 12h5m0 0l-3-3m3 3l-3 3"/></svg>
            Glissez pour explorer nos moments
          </p>
        </div>
        <div class="gallery__track" data-drag>
          <?php foreach ($gallery as $shot): ?>
            <figure class="shot<?= !empty($shot['wide']) ? ' shot--wide' : '' ?>"><img src="<?= e($shot['src']) ?>" alt="<?= e($shot['caption'] ?? 'Séminaire CLEV Africa') ?>" loading="lazy" draggable="false"><figcaption><?= e($shot['caption'] ?? '') ?></figcaption></figure>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- REFERENCES -->
    <section class="refs section" id="references">
      <div class="container">
        <div class="section__head section__head--center">
          <p class="eyebrow" data-reveal><span class="eyebrow__dot"></span>Nos références</p>
          <h2 class="title" data-split>Ils nous ont fait et continuent de nous faire <em>confiance</em>.</h2>
          <p class="section__lead" data-reveal data-delay="0.15">Banques, industrie, télécoms, énergie, logistique, institutions publiques&nbsp;: des acteurs majeurs de la sous-région nous confient leurs enjeux.</p>
        </div>

        <ul class="logos" data-reveal>
          <li class="logo" data-spot><img src="assets/partners/ecobank.svg" alt="Ecobank" loading="lazy"><span>Ecobank</span></li>
          <li class="logo" data-spot><img src="assets/partners/socacig.svg" alt="SOCACIG" loading="lazy"><span>SOCACIG</span></li>
          <li class="logo" data-spot><img src="assets/partners/bpmc.png" alt="BPMC" loading="lazy"><span>BPMC</span></li>
          <li class="logo" data-spot><img src="assets/partners/mocaf.jpg" alt="MOCAF" loading="lazy"><span>MOCAF</span></li>
          <li class="logo" data-spot><img src="assets/partners/telecel.png" alt="Telecel RCA" loading="lazy"><span>Telecel</span></li>
          <li class="logo" data-spot><img src="assets/partners/mercure.png" alt="Mercure Logistics Centrafrique" loading="lazy"><span>Mercure Logistics</span></li>
          <li class="logo" data-spot><img src="assets/partners/bsic.png" alt="BSIC" loading="lazy"><span>BSIC</span></li>
          <li class="logo" data-spot><img src="assets/partners/cnss.jpg" alt="CNSS" loading="lazy"><span>CNSS</span></li>
          <li class="logo" data-spot><img src="assets/partners/cfao.jpg" alt="CFAO Mobility" loading="lazy"><span>CFAO Mobility</span></li>
          <li class="logo" data-spot><img src="assets/partners/burval.svg" alt="Burval" loading="lazy"><span>Burval</span></li>
          <li class="logo" data-spot><img src="assets/partners/onm.jpg" alt="Office National du Matériel" loading="lazy"><span>ONM</span></li>
          <li class="logo" data-spot><img src="assets/partners/tradex.png" alt="Tradex" loading="lazy"><span>Tradex</span></li>
          <li class="logo" data-spot><img src="assets/partners/enerca.jpg" alt="ENERCA" loading="lazy"><span>ENERCA</span></li>
          <li class="logo" data-spot><img src="assets/partners/sodeca.jpg" alt="Sodeca" loading="lazy"><span>Sodeca</span></li>
          <li class="logo" data-spot><img src="assets/partners/pad.png" alt="Port Autonome de Douala" loading="lazy"><span>PAD — Représentation RCA</span></li>
          <li class="logo" data-spot><img src="assets/partners/orange.svg" alt="Orange" loading="lazy"><span>Orange</span></li>
          <li class="logo" data-spot><img src="assets/partners/agora.jpg" alt="Agora" loading="lazy"><span>Agora</span></li>
          <li class="logo" data-spot><img src="assets/partners/bgfi.jpg" alt="BGFIBank" loading="lazy"><span>BGFIBank</span></li>
          <li class="logo" data-spot><img src="assets/partners/tamoil.svg" alt="Tamoil" loading="lazy"><span>Tamoil</span></li>
          <li class="logo" data-spot><img src="assets/partners/sica.svg" alt="SICA Sécurité" loading="lazy"><span>SICA Sécurité</span></li>
        </ul>
      </div>
    </section>

    <!-- PRESENCE -->
    <section class="presence section section--dark" id="presence">
      <div class="container">
        <div class="section__head">
          <p class="eyebrow" data-reveal><span class="eyebrow__dot"></span>Notre présence</p>
          <h2 class="title" data-split>Deux bureaux, <em>un continent</em>.</h2>
        </div>
        <div class="offices">
          <article class="office" data-spot data-reveal>
            <span class="office__flag" aria-hidden="true">
              <svg viewBox="0 0 30 20"><rect width="10" height="20" fill="#007a5e"/><rect x="10" width="10" height="20" fill="#ce1126"/><rect x="20" width="10" height="20" fill="#fcd116"/><path fill="#fcd116" d="M15 6l1.1 3.3H19.6l-2.8 2 1.1 3.3L15 12.6l-2.9 2 1.1-3.3-2.8-2h3.5z"/></svg>
            </span>
            <h3>Douala <small>Cameroun</small></h3>
            <p>Porte d'entrée économique de l'Afrique centrale, notre bureau de Douala accompagne les entreprises implantées au Cameroun et en zone CEMAC.</p>
            <a href="tel:+237675217369">+237 675 21 73 69</a>
          </article>
          <article class="office" data-spot data-reveal data-delay="0.12">
            <span class="office__flag" aria-hidden="true">
              <svg viewBox="0 0 30 20"><rect width="30" height="5" fill="#003082"/><rect y="5" width="30" height="5" fill="#fff"/><rect y="10" width="30" height="5" fill="#289728"/><rect y="15" width="30" height="5" fill="#ffce00"/><rect x="12" width="6" height="20" fill="#d21034"/><path fill="#ffce00" d="M5 1l.8 2.3h2.4L6.3 4.7l.7 2.3L5 5.6 3 7l.7-2.3L1.8 3.3h2.4z"/></svg>
            </span>
            <h3>Bangui <small>République Centrafricaine</small></h3>
            <p>Avenue Boganda, au-dessus de la BSIC — Lakouanga<br>BP 2250 Bangui, RCA</p>
            <a href="tel:+23676184343">+236 76 18 43 43</a> · <a href="tel:+23674403171">+236 74 40 31 71</a>
          </article>
        </div>
      </div>
    </section>

    <!-- CONTACT -->
    <section class="contact section" id="contact">
      <div class="container contact__grid">
        <div class="contact__text">
          <p class="eyebrow" data-reveal><span class="eyebrow__dot"></span>Contact</p>
          <h2 class="title" data-split>Parlons de <em>votre</em> projet.</h2>
          <p data-reveal data-delay="0.1">Un besoin ponctuel ou un accompagnement dans la durée&nbsp;? Décrivez-nous votre situation, un expert vous répond sous 48&nbsp;heures.</p>

          <ul class="contact__list" data-reveal data-delay="0.2">
            <li>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v12H4zM4 7l8 6 8-6"/></svg>
              <a href="mailto:contact@clevafricaconsulting.com">contact@clevafricaconsulting.com</a>
            </li>
            <li>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/></svg>
              <span><a href="tel:+23676184343">+236 76 18 43 43</a> · <a href="tel:+237675217369">+237 675 21 73 69</a></span>
            </li>
            <li>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-6.5-7-11a7 7 0 0114 0c0 4.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
              <span>Douala, Cameroun · Bangui, RCA</span>
            </li>
            <li>
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9v9M6 5v.5M10 18v-9M10 13c0-2.5 1.5-4 4-4s4 1.5 4 4v5"/></svg>
              <a href="https://www.linkedin.com/company/clevafricaconsulting/" target="_blank" rel="noopener">Suivez-nous sur LinkedIn</a>
            </li>
          </ul>
        </div>

        <form class="form" id="form" data-reveal data-delay="0.15" novalidate>
          <div class="form__row">
            <label class="field">
              <span>Nom &amp; prénom</span>
              <input type="text" name="nom" placeholder="Ex. Awa Mbaye" required autocomplete="name">
            </label>
            <label class="field">
              <span>Entreprise</span>
              <input type="text" name="entreprise" placeholder="Votre société" autocomplete="organization">
            </label>
          </div>
          <div class="form__row">
            <label class="field">
              <span>E-mail</span>
              <input type="email" name="email" placeholder="vous@entreprise.com" required autocomplete="email">
            </label>
            <label class="field">
              <span>Téléphone</span>
              <input type="tel" name="tel" placeholder="+236 / +237 …" autocomplete="tel">
            </label>
          </div>
          <label class="field">
            <span>Domaine concerné</span>
            <select name="domaine">
              <?php foreach (CONTACT_DOMAINES as $opt): ?>
                <option<?= $opt === $prefDomaine ? ' selected' : '' ?>><?= e($opt) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label class="field">
            <span>Votre message</span>
            <textarea name="message" rows="4" placeholder="Décrivez brièvement votre besoin…" required><?= e($prefSujet) ?></textarea>
          </label>
          <button type="submit" class="btn btn--primary btn--block" data-magnetic>
            <span>Envoyer ma demande</span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </button>
          <p class="form__note" id="formNote" role="status" aria-live="polite"></p>
        </form>
      </div>
    </section>
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
        <a href="#a-propos">À propos</a>
        <a href="expertises.php">Expertises</a>
        <a href="#approche">Approche</a>
        <a href="formations.php">Formations</a>
        <a href="#references">Références</a>
        <a href="#contact">Contact</a>
      </nav>

      <div class="footer__col">
        <h4>Expertises</h4>
        <a href="expertises.php#juridique">Juridique</a>
        <a href="expertises.php#fiscalite">Fiscalité</a>
        <a href="expertises.php#social-paie">Social &amp; paie</a>
        <a href="expertises.php#douane-changes">Douane &amp; changes</a>
        <a href="expertises.php#prix-transfert">Prix de transfert</a>
        <a href="expertises.php#ressources-humaines">Ressources humaines</a>
        <a href="expertises.php#gouvernance">Gouvernance</a>
      </div>

      <div class="footer__col">
        <h4>Contact</h4>
        <a href="mailto:contact@clevafricaconsulting.com">contact@clevafricaconsulting.com</a>
        <a href="tel:+23676184343">+236 76 18 43 43</a>
        <a href="tel:+23674403171">+236 74 40 31 71</a>
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
