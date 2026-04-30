# Eco Ride - SOUAL Clara
## Projet ECF - Session Juin/Juillet - Graduate Développeur Web

## Présentation du projet
Eco Ride est un site de covoiturage éco-responsable, respectueux de l'environnement.
Le projet est un site full-stack PHP avec une base de données relationnelle MySQL et un déploiement en production.

## Lancer le projet en local avec XAMPP
1. Installer XAMPP (ou un autre serveur local).
2. Placer le dossier `eco_ride` dans le dossier `htdocs` de XAMPP.
3. Démarrer Apache et MySQL via le panneau de contrôle XAMPP.
4. Importer le fichier `eco_ride.sql` dans phpMyAdmin.
5. Ouvrir un navigateur et aller à `http://localhost/eco_ride/PROJET/UTILISATEUR/USR-index.php`.

## Lancer le projet en local avec Docker
1. Installer Docker Desktop.
2. À la racine du projet, lancer :
```bash
docker-compose up --build
```
3. Accéder au site sur `http://localhost:8080`.
4. Accéder à phpMyAdmin sur `http://localhost:8081`.

## Déploiement en production
Le site est déployé sur Railway : https://ecfsoualclara-production.up.railway.app

- Serveur : Railway (Docker, nginx + php-fpm)
- Base de données : MySQL hébergée sur Railway
- Variables d'environnement configurées sur Railway : `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`

## Structure du projet
```
eco_ride/
├── IMAGES/
├── PROJET/
│   ├── ADMIN/
│   ├── COMPONENTS/
│   ├── CSS/
│   ├── EMPLOYE/
│   ├── JS/
│   ├── PHP/
│   ├── SQL/
│   └── UTILISATEUR/
├── Dockerfile
├── nginx.conf
├── start.sh
└── docker-compose.yml
```

## Charte graphique
Le design du site Eco Ride a été pensé pour refléter des valeurs écologiques et modernes.

**Couleurs principales** :
- Vert doux : symbolise la nature et l'écologie.
- Orange doux : pour une touche de modernité.
- Beige foncé et gris : créent du contraste avec les éléments du site.

**Police** :
- Police sans-sérif, moderne et lisible, pour assurer un bon confort de lecture.

**Responsive design** :
- Le site est adapté pour s'afficher correctement sur tablette et mobile.

## Technologies utilisées
- HTML5, CSS3, JavaScript
- PHP 8.2
- MySQL
- Docker (nginx + php-fpm)
- Railway (déploiement)
- XAMPP (développement local)
- Visual Studio Code
- Git et GitHub
- Trello pour la gestion de projet

## Auteur
Clara SOUAL
Étudiante Studi Graduate Développeur Web - Session Juin/Juillet 2026
GitHub : https://github.com/clarasoual/ECF_SOUAL_CLara
Email : soual.clara@gmail.com
