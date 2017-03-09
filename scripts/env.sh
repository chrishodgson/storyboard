#!/bin/bash

# your database options
DBHOST='localhost'
DBUSER='root'
DBPW=''
#for individual databases uncomment this and put in the name of your database :
DBNAME=('storyboard')
#for all databases the user has access to, keep this uncommented:
#DBNAME=( `echo "show databases" |  mysql --user=$DBUSER --password=$DBPW --host=$DBHOST  | tail -n+3 `)

# your folders to backup
FOLDERS=('/home/chris/storyboard')

# num days of backups to retain
DAYS=7