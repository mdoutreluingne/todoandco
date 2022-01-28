ToDo & Co
========

Ce dépôt contient le code du projet 8 réalisé durant ma formation de développpeur PHP/Symfony à OpenClassrooms.
Le projet est une application de To Do list et présente les fonctionnalités suivantes :
* Espace utilisateur
* Espace utilisateurs admin permettant de modifier les autres utilisateurs
* Ajout, édition et suppression de tâches à faire
* Possibilité de marquer une tâche en terminée
* Visualisation de la liste des tâches à faire/terminées

[![Maintainability](https://api.codeclimate.com/v1/badges/5a0e321da1ca7af9a15e/maintainability)](https://codeclimate.com/github/mdoutreluingne/todoandco/maintainability)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/b189f76f4a214839bafaf983085c23f7)](https://www.codacy.com/gh/mdoutreluingne/todoandco/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=mdoutreluingne/todoandco&amp;utm_campaign=Badge_Grade)

## Configuration du serveur requise

*   MySQL ou MariaDB
*   Apache2 (avec le mod_rewrite activé)
*   Php 7.4
*   Composer
*   Git

## Installation du projet

Cloner le projet sur votre disque dur avec la commande :
```text
https://github.com/mdoutreluingne/todoandco.git
```

Ensuite, effectuez la commande "composer install" depuis le répertoire du projet cloné, afin d'installer les dépendances back nécessaires :
```text
composer install
```

### Paramétrage et accès à la base de données

Editez le fichier situé à la racine intitulé ".env" afin de remplacer les valeurs de paramétrage de la base de données :

````text
//Exemple : mysql://root:@127.0.0.1:3306/todoandco
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
````

Ensuite à la racine du projet, effectuez la commande `php bin/console doctrine:database:create` pour créer la base de données :

````text
php bin/console doctrine:database:create
````

Pour obtenir une structure similaire à mon projet au niveau de la base de données, je vous joins aussi dans le dossier `~src/migrations/` les versions de migrations que j'ai utilisées. Vous pouvez donc recréer la base de données en effectuant la commande suivante, à la racine du projet :

```text
php bin/console doctrine:migrations:migrate
```

Après avoir créer votre base de données, vous pouvez également injecter un jeu de données en effectuant la commande suivante :

```text
php bin/console doctrine:fixtures:load
```

### Lancer le projet

A la racine du projet :

*   Pour lancer le serveur de symfony, effectuez un `php bin/console server:start`.

### Bravo, l'application est désormais accessible à l'adresse : localhost:8000

### Identifiant de connexion

*   Nom d'utilisateur : admin
*   Mot de passe : adminadmin

## Tests

La première étape consiste à configurer votre environnement, en environnement de test en modifiant
la variable `APP_ENV` dans le fichier `.env`.

```text
APP_ENV=test
```

Ensuite, initialisez la base de données pour l'environnement de test avec les commandes suivantes :

```text
php bin/console doctrine:database:create --env=test
```
Puis

```text
php bin/console doctrine:migrations:migrate --env=test
```

Pour finir les données de test

```text
php bin/console doctrine:fixtures:load
```

Si vous souhaitez lancer les tests unitaires et fonctionnels et créer un rapport de couverture de code, lancez la commande suivante à la racine du projet : 

```text
php bin/phpunit --coverage-html web/test-coverage
```
Le rapport sera produit en HTML et disponible dans le dossier web/test-coverage.

## Contribuer

Les contributions, problèmes et demandes de fonctionnalités sont les bienvenus.

Afin de contribuer à ce projet, merci de lire les instructions contenues dans [Guide de contribution](https://github.com/mdoutreluingne/todoandco/blob/master/CONTRIBUTING.md)
