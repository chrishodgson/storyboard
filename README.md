Console commands
===

- Create Database using symfony config
php bin/console doctrine:database:create

- Drop Database using symfony config
php bin/console doctrine:database:drop --force

- Generate Database tables using symfony entities
php bin/console doctrine:schema:update --force

- Create test data (purging tables first)
php bin/console doctrine:fixtures:load  

- Create just the languages (without purging tables first) 
php bin/console doctrine:fixtures:load --append ??? 
