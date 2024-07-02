# ToDo & Co

ToDo & Co est une application de gestion de tâches quotidienne développée avec le framework Symfony. Ce projet a été initialement créé en tant que Minimum Viable Product (MVP) et a été amélioré pour inclure de nouvelles fonctionnalités, corriger des bugs et ajouter des tests automatisés.

## Table des matières

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [Tests](#tests)
- [Contribuer](#contribuer)
- [Diagrammes UML](#diagrammes-uml)
- [Licence](#licence)

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL ou tout autre SGBD compatible avec Doctrine
- Node.js et npm pour la gestion des assets

## Installation

1. Clonez le repository :

    ```bash
    git clone https://github.com/username/todo-co.git
    cd todo-co
    ```

2. Installez les dépendances PHP avec Composer :

    ```bash
    composer install
    ```

3. Installez les dépendances JavaScript avec npm :

    ```bash
    npm install
    ```

4. Compilez les assets :

    ```bash
    npm run dev
    ```

## Configuration

1. Copiez le fichier `.env` et renommez-le en `.env.local` :

    ```bash
    cp .env .env.local
    ```

2. Configurez les variables d'environnement dans le fichier `.env.local` :

    ```env
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
    ```

3. Créez la base de données et exécutez les migrations :

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

4. Chargez les fixtures pour créer des utilisateurs et des tâches de test :

    ```bash
    php bin/console doctrine:fixtures:load
    ```

## Utilisation

Démarrez le serveur de développement Symfony :

```bash
php bin/console server:run
```

## Tests

1. Installez PHPUnit si ce n'est pas déjà fait :

```bash
composer require --dev phpunit/phpunit
```

2. Exécutez les tests :

```bash
php bin/phpunit
```

