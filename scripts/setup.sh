#!/bin/bash

echo 'Starting script...'

# run composer
while true; do
    read -p "Do you wish to run composer ? Y/N " answer
    case $answer in
        [Yy]* ) composer install -o; break;;
        [Nn]* ) break;;
        * ) echo "Please answer Y or N.";;
    esac
done

# install assets
while true; do
    read -p "Do you wish to dump assets ? Y/N " answer
    case $answer in
        [Yy]* ) php bin/console assets:install;
                php bin/console assetic:dump; break;;
        [Nn]* ) break;;
        * ) echo "Please answer Y or N.";;
    esac
done

# drop database and run migrations
while true; do
    read -p "Do you wish to drop the database and run the migrations ? Y/N " answer
    case $answer in
        [Yy]* ) php bin/console doctrine:database:drop --force;
                php bin/console doctrine:database:create;
                php bin/console doctrine:migrations:migrate; break;;
        [Nn]* ) break;;
        * ) echo "Please answer Y or N.";;
    esac
done

# run the lookup data fixtures
while true; do
    read -p "Do you wish to run the lookup fixtures ? Y/N " answer
    case $answer in
        [Yy]* ) php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/LookupData --append; break;;
        [Nn]* ) break;;
        * ) echo "Please answer Y or N.";;
    esac
done

# run the dummy data fixtures
while true; do
    read -p "Do you wish to run the dummy data fixtures ? Y/N " answer
    case $answer in
        [Yy]* ) php bin/console doctrine:fixtures:load --fixtures=src/AppBundle/DataFixtures/ORM/DummyData --append; break;;
        [Nn]* ) break;;
        * ) echo "Please answer Y or N.";;
    esac
done

echo 'Finished script.'
