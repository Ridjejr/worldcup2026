# World Cup 2026 Tracker

Application web de suivi de la Coupe du Monde 2026 permettant de consulter :

- Les matchs par phase
- Les groupes et équipes
- Les détails d’un match
- Les scores en temps réel (via API Football)
- La navigation entre phases et groupes

Projet réalisé dans un cadre académique pour mettre en pratique :
- Conception UML / MERISE
- Développement full-stack
- Intégration d’API externes
- Gestion de projet Agile
- Travail collaboratif avec Git

---

# Technologies utilisées

## Back-end
- PHP 8+
- Symfony 6+
- Doctrine ORM
- MySQL

## Front-end
- Twig
- HTML / CSS
- Bootstrap (si utilisé)
  
## Outils
- GitHub (versioning)
- Jira (Kanban)
- MySQL Workbench
- API-Football (API-SPORTS)

---

# Installation du projet

## Cloner le projet

    ```bash
    git clone https://github.com/<votre-repo>/worldcup2026.git
    cd worldcup2026

Installer les dépendances

    ```bash
    composer install

Configuration environnement

Créer un fichier .env.local :

    ```bash
    APP_ENV=dev
    APP_SECRET=secret

    DATABASE_URL="mysql://root:@127.0.0.1:3306/cdw2026?serverVersion=8.0.32&charset=utf8mb4"

    APIFOOTBALL_KEY=VOTRE_CLE_API
    APIFOOTBALL_LEAGUE_ID=1
    APIFOOTBALL_SEASON=2022

Ne jamais commit .env.local
Base de données

Créer la base :

    ```bash
    php bin/console doctrine:database:create

Créer les tables :

    ```bash
    php bin/console doctrine:schema:update --force

Charger les données de test (fixtures) pour tester sans api

    ```bash
    php bin/console doctrine:fixtures:load

Cela ajoute :

    Edition 2026

    Phases

    Groupes

    Equipes

    Matchs

Lancer le serveur

    ```bash
    symfony server:start
    ou
    php -S 127.0.0.1:8000 -t public

Puis ouvrir :

    ```bash
    http://127.0.0.1:8000

API Football

Le projet utilise l’API-SPORTS (API Football) pour :

    Récupérer les matchs

    Obtenir les détails en temps réel

    Mettre à jour scores et événements

Commande d’import :

    ```bash
    php bin/console app:api-football:import

Architecture du projet
Entités principales

    Edition

    Phase

    Groupe

    Equipe

    Stade

    WorldcupMatch

    Participer (relation match-équipe)

Fonctionnalités principales
Page d’accueil

    Liste des matchs

    Filtrage par phase

    Navigation entre phases

Page Groupes

    Vue de tous les groupes

    Equipes par groupe

Détail d’un match

    Equipes + drapeaux

    Stade

    Date et heure

    Score live (API)

    Simulation live possible

Organisation de l’équipe
Rôles
Junior

Responsable de projet / Scrum Master

    Organisation du travail

    Coordination équipe

    Suivi Kanban

Amadou

Développeur Back-end

    BDD

    Entités Doctrine

    API integration

    Logique métier

Lucas

Développeur Front-end

    Templates Twig

    Navigation

    UI/UX

    Pages groupes/matchs

Méthodologie

Approche Agile adaptée :

    Mini-sprints sur 3 jours

    Objectifs journaliers

    Points rapides quotidiens

    Rétrospective en fin de projet

Gestion Git
Branches

    main → version stable

    Dev/Amadou → back-end

    dev_lucas → front-end

Règles de commit

Commits :

    fréquents

    clairs

    centrés sur une tâche

Exemples :

feat: ajout page groupes
fix: correction import API
refactor: amélioration entité match

Pull Requests

Utilisées pour :

    revue de code

    validation avant merge

    résolution collaborative des conflits

Kanban

Gestion des tâches via Jira :

Colonnes :

    À faire

    En cours

    Terminé

Chaque tâche :

    assignée

    estimée

    suivie quotidiennement

Conception

Documents réalisés :

    MCD / MLD / MPD

    Diagrammes UML

    Wireframes

    Maquettes

    Charte graphique

    Dictionnaire de données

Limitations

    Données 2026 simulées (compétition non encore jouée)

    Dépendance API externe

    Version académique simplifiée

Améliorations possibles

    Authentification utilisateur

    Favoris équipes/matchs

    Notifications live

    Statistiques avancées

    Responsive mobile amélioré

Licence

Projet académique – usage pédagogique.



