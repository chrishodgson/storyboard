Install
===

git clone 

php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/LookupData --append

Command line options
===

From the main storyboard folder...

- Create Database using symfony config
php bin/console doctrine:database:create

- Drop Database using symfony config
php bin/console doctrine:database:drop --force

- Generate Database tables using symfony entities
php bin/console doctrine:schema:update --force

- Seed the database with lookup data (ie languages) 
php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/LookupData --append

- Seed the database with dummy data (ie stories & snippets)
php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/DummyData --append


