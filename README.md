Description
===
PHP 7.1 / Symfony 3 web application which allows you to organise code snippets by story & project.
Includes laradock as a git sub module so can either be run via docker or directly on the local machine.

Run directly on local machine
===

System requirements
==
- PHP >=7.1
- MySQL (or other supported database) # http://www.doctrine-project.org/2010/02/11/database-support-doctrine2.html 
- composer # See https://getcomposer.org/ for more information and documentation.

Installation 
== 
- clone the repository: `git clone https://github.com/chrishodgson/storyboard.git` 
- cd into the repository folder and run the setup script: `./scripts/setup.sh`                      

Installation using Laradock (docker)
===

System requirements
==
- Docker

Installation 
== 
- install docker: `git clone https://github.com/chrishodgson/storyboard.git` 
- TODO: setup laradock config 
    - symfony-laradock/nginx/sites/symfony.conf
- cd into the repository folder and run `docker-compose up -d nginx mariadb`                       

end.