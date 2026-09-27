# CLEV Africa Consulting — Site vitrine

Site du cabinet CLEV Africa Consulting. Pages en PHP (PHP ≥ 8.0), contenus administrables via un dashboard.

## Lancer en local

```bash
php -S 127.0.0.1:8000
```

Puis ouvrir http://127.0.0.1:8000.

## Pages

- `index.php` — page d'accueil (hero, à propos, expertises, approche, formations, galerie, références, bureaux, contact)
- `expertises.php` — détail des domaines d'expertise et abonnements annuels
- `formations.php` — catalogue des séminaires et formations
- `admin.php` — dashboard d'administration

## Administration

Ouvrir http://127.0.0.1:8000/admin.php. Le mot de passe par défaut est `clevadmin`.

Pour le changer, définir la variable d'environnement `ADMIN_PASSWORD` ou créer un fichier `data/.admin_password` contenant le mot de passe.

Depuis le dashboard on peut ajouter, modifier et supprimer :

- les **expertises** (titre, résumé, liste des prestations — une par ligne, image)
- les **formations** (titre, méta, description, image)
- les **abonnements** (nom, prix, description)
- les **images du site** : hero, image « À propos », affiche séminaire, et la galerie photos de l'accueil (upload/suppression, légendes, format large)

Les contenus sont stockés dans `data/content.json` (sauvegarde automatique `.bak` à chaque écriture) et les fichiers envoyés dans `uploads/` (JPG, PNG, WebP, SVG, GIF — 8 Mo max). Seules les images issues d'`uploads/` sont supprimées physiquement ; les fichiers d'`assets/` ne sont jamais effacés.

## Déploiement Docker + Traefik

Même architecture qu'AGEO : un Traefik global sur le VPS installé dans `/opt/traefik/` (réseau externe `traefik-proxy`, entrypoints `web`/`websecure`, resolver `letsencrypt`, redirection HTTP → HTTPS globale). Si Traefik n'est pas encore installé, le dossier `traefik/` contient tout le nécessaire (voir `traefik/README.md`) :

```bash
docker network create traefik-proxy   # une seule fois
cd /opt/traefik && docker compose up -d
```

Puis déployer le site :

```bash
docker compose up -d --build
```

Le site est ensuite servi sur **https://clev-africa.mbila.pro** (HTTP → HTTPS automatique, certificat Let's Encrypt). Le DNS de `clev-africa.mbila.pro` doit pointer vers le serveur.

- `clev-data` et `clev-uploads` sont des volumes nommés : le contenu et les images survivent aux rebuilds ; le contenu de `data/` est copié depuis l'image au premier démarrage
- `data/` est bloqué côté Apache (`docker/deny-data.conf`) — `content.json` et `.admin_password` ne sont pas exposés
- Mot de passe admin : variable `ADMIN_PASSWORD` (fichier `.env`)

## Structure

- `inc.php` — helpers partagés (lecture/écriture du JSON, uploads, auth, header/footer)
- `css/styles.css` — design system et animations
- `css/admin.css` — styles du dashboard
- `js/main.js` — interactions (révélations au scroll, curseur, menu mobile, formulaire…)
- `data/content.json` — contenus administrables
- `uploads/` — images envoyées depuis le dashboard
- `assets/` — logo, photos, logos des références
