# Symfony project

> Simple Symfony Project using Controller-Service-Repository model.

Created by Filip Duber.


## What is inside?
* Apache 2.4.57 (Debian)
* PHP 8.3 FPM
* MySQL 8.3.1
* NodeJS LTS (latest)
* Composer
* Symfony CLI 
* xdebug
* djfarrelly/maildev

## Installation Procedure

1. Build Docker containers with ``docker-compose build``
2. Enable containers with ``docker-compose up -d``
   - To disable containers ``docker-compose down``
3. In your project directory install dependencies with `composer-install`
    - To update them use ``composer-update``
4. Set up your DATABASE_URL in ``.env`` and ``.env.test`` to enable database access.
5. Enter PHP container ``docker-compose exec php bash`` and go do `/app` directory
6. Migrate migrations with ``bin/console doctrine:migrations:migrate``
7. Load data fixtures with ``bin/console doctrine:fixtures:load``

## Other useful commands
- Cache clear ``php bin/console cache:clear``