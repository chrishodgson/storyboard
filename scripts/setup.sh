#!/bin/bash

echo 'Starting script...'

# composer update - TODO add this in
# composer update -o

# install assets
php bin/console assets:install

# drop the database
php bin/console doctrine:database:drop --force

# create the database
php bin/console doctrine:database:create

# run the schema migrations
php bin/console doctrine:migrations:migrate

# run the lookup data fixtures
php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/LookupData --append

# do we want to run the dummy data fixtures
while true; do
    read -p "WARNING! You are about to install dummy data. Do you wish to continue ?" yn
    case $yn in
        [Yy]* ) php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/DummyData --append; break;;
        [Nn]* ) exit;;
        * ) echo "Please answer yes or no.";;
    esac
done

echo 'Finished script.'
