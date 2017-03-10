Description
===
This is a web application build using the Symfony 3 PHP framework which allows you to store code snippets grouped 
into stories.


System requirements
===
- Mysql database
- PHP >=7
- composer

Installation instructions
===

- git clone https://github.com/chrishodgson/storyboard.git # or download
- composer install 
- php bin/console doctrine:database:create
- php bin/console doctrine:schema:update --force
- php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/LookupData --append

Useful command line options
===

From the folder where you installed the application (ie storyboard) ...

- Drop Database using symfony config
php bin/console doctrine:database:drop --force

- Create Database using symfony config
php bin/console doctrine:database:create

- Generate Database tables 
    - using symfony entities
    php bin/console doctrine:schema:update --force

    - using symfony migrations
    php bin/console doctrine:migrations:migrate

- Seed the database with lookup data (ie languages) 
php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/LookupData --append

- Seed the database with dummy data (ie stories & snippets)
php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/DummyData --append


