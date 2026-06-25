# Eco Ride — SOUAL Clara
## Projet ECF — Session Juin/Juillet 2026 — Titre Professionnel DWWM

## Présentation du projet
Eco Ride est une plateforme de covoiturage éco-responsable permettant aux utilisateurs de proposer ou réserver des trajets, tout en limitant l'impact environnemental des déplacements.

Le projet est un site full-stack PHP avec une base de données relationnelle MySQL, un système de logs NoSQL (JSON), et un déploiement en production via Docker et Railway.

**Rôles disponibles :** Visiteur · Utilisateur (Passager / Conducteur / Passager-Conducteur) · Employé · Administrateur

---

## Comptes de démonstration

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | clara@admin-eco.com | [ à compléter ] |
| Employé | balthazar@emp-eco.com | Mdp12345! |
| Utilisateur | nino@example.com | Mdp12345! |

---

## Lancer le projet en local avec XAMPP

1. Installer [XAMPP](https://www.apachefriends.org/)
2. Placer le dossier `eco_ride` dans le dossier `htdocs` de XAMPP
3. Démarrer Apache et MySQL via le panneau de contrôle XAMPP
4. Dans phpMyAdmin, créer une base de données nommée `eco_ride`
5. Importer le fichier `PROJET/SQL/eco_ride.sql` (structure + données seed)
6. Ouvrir un navigateur et aller à :
   `http://localhost/eco_ride/PROJET/UTILISATEUR/USR-index.php`

---

## Lancer le projet en local avec Docker

1. Installer [Docker Desktop](https://www.docker.com/products/docker-desktop/)
2. À la racine du projet, lancer :
```bash
docker-compose up --build
```
3. Accéder au site sur `http://localhost:8080`
4. Accéder à phpMyAdmin sur `http://localhost:8081`

---

## Déploiement en production

Le site est déployé sur Railway : `[ URL de production à compléter après redéploiement ]`

- Serveur : Railway (Docker, nginx + php-fpm)
- Base de données : MySQL hébergée sur Railway
- Variables d'environnement à configurer sur Railway :

| Variable | Description |
|----------|-------------|
| `DB_HOST` | Hôte MySQL Railway |
| `DB_PORT` | Port MySQL (3306) |
| `DB_NAME` | Nom de la BDD (eco_ride) |
| `DB_USER` | Utilisateur MySQL |
| `DB_PASS` | Mot de passe MySQL |
| `APP_ENV` | `production` |

---

## Structure du projet

```
eco_ride/
├── IMAGES/
│   └── profiles/        # Photos de profil utilisateurs
├── PROJET/
│   ├── ADMIN/           # Pages espace administrateur
│   ├── COMPONENTS/      # Header, footer (composants PHP réutilisables)
│   ├── CSS/             # Feuilles de style
│   ├── EMPLOYE/         # Pages espace employé
│   ├── JS/              # Scripts JavaScript
│   ├── PHP/             # Logique back-end (auth, BDD, traitement formulaires)
│   ├── SQL/             # Script SQL (création tables + seed)
│   └── UTILISATEUR/     # Pages espace utilisateur
├── dossier_projet/      # Documentation complète du projet
│   ├── documentation_technique.pdf   # MCD, diagrammes, choix techniques [ page X ]
│   └── gestion_de_projet.pdf         # Kanban, méthodologie [ page X ]
├── Dockerfile
├── nginx.conf
├── start.sh
└── docker-compose.yml
```

---

## Documentation

La documentation complète est disponible dans le dossier `dossier_projet/` à la racine :

- **Documentation technique** — MCD, diagrammes UML, architecture, choix techniques, déploiement
- **Documentation gestion de projet** — Kanban Trello, méthodologie, organisation
- **Manuel utilisateur** — Parcours par rôle avec captures d'écran
- **Charte graphique** — Palette de couleurs, typographie, composants UI

Lien Trello : `[ à compléter ]`

---

## Technologies utilisées

- HTML5, CSS3, JavaScript (vanilla)
- PHP 8.2
- MySQL / phpMyAdmin
- JSON (système de logs NoSQL maison)
- Docker (nginx + php-fpm)
- Railway (déploiement)
- XAMPP (développement local)
- Visual Studio Code
- Git et GitHub
- Trello (gestion de projet)
- Figma (maquettes)
- PHPMailer (envoi de mails)

---

## Auteur

**Clara SOUAL**
Étudiante Studi — Titre Professionnel DWWM — Session Juin/Juillet 2026
GitHub : https://github.com/clarasoual/ECF_SOUAL_CLara
Email : soual.clara@gmail.com