# World Cup 2026 Tracker

Application de suivi de la Coupe du Monde 2026 (matchs, phases, groupes, détails de match, scores).

## Technologies
- Symfony (PHP)
- Doctrine ORM
- MySQL
- Twig (front)
- API Football (API-SPORTS) pour données externes

## Installation
1. Cloner le dépôt :
   ```bash
   git clone <repo-url>
   cd worldcup2026
   
Installer les dépendances :

    ```bash
    composer install

Configurer la base de données dans .env.local :

    ``bash
    DATABASE_URL="mysql://root:@127.0.0.1:3306/cdw2026?serverVersion=8.0.32&charset=utf8mb4"
    APIFOOTBALL_KEY=VOTRE_CLE_API
    APIFOOTBALL_LEAGUE_ID=1
    APIFOOTBALL_SEASON=2022

Créer la base + schéma :

    ``bash
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force

Lancer le serveur :

    ``bash
    symfony server:star 
    ou 
    php -S 127.0.0.1:8000 -t public

Import API

    ``bash
    php bin/console app:api-football:import
