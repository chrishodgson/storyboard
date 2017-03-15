#!/bin/bash

echo 'Starting script...'

# drop the database
php bin/console doctrine:database:drop --force

# create the database
php bin/console doctrine:database:create

# run the schema migrations
php bin/console doctrine:migrations:migrate

# run the lookup data fixtures
php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/LookupData --append

# run the dummy data fixtures
php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/DummyData --append

echo 'Finished script.'
