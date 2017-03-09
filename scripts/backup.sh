#!/bin/bash

# cp to ~/.scripts
# run from crontab using 0 0 * * * /home/chris/.scripts/backup

#Options##############

# source the following settings from file env.sh
. env.sh

# database options
#DBHOST='localhost'
#DBUSER='root'
#DBPW=''
#for individual databases uncomment this and put in the name of your database :
#DBNAME=('storyboard')
#for all databases the user has access to, keep this uncommented:
#DBNAME=( `echo "show databases" |  mysql --user=$DBUSER --password=$DBPW --host=$DBHOST  | tail -n+3 `)

# folders to backup
#FOLDERS=(  '/home/chris/storyboard'  )

 #Testmode# if you aren't sure just leave this #######
 #use MODE='-v' to see some output for testing
 #use MODE='-q' to run quiet once you have the script working
     MODE='-q'


#End Options #stop editing####



      #if running in -v mode(verbose),  give some output
       if [ "$MODE" = "-v" ]; then
       echo "check if local .backups directory exists. if not create it";
       fi

       #check if local .backups directory exists. if not create it
       if [ ! -d "~/.backups/$SCRIPTNAME/db" ]; then
       mkdir -p ~/.backups/$SCRIPTNAME/db ;
       fi

       #if running in -v mode(verbose),  give some output
       if [ "$MODE" = "-v" ]; then
       echo "remove db files older than 1 day";
       fi

       find ~/.backups/$SCRIPTNAME/db/*.sql -type f -daystart -mtime +$DAYS -exec rm {} \;

       #if running in -v mode(verbose),  give some output
       if [ "$MODE" = "-v" ]; then
       echo "get recent version of databases";
       fi

       #get recent version of databases from array
       for i in "${DBNAME[@]}"
       do
       mysqldump --opt --user=$DBUSER --password=$DBPW --host=$DBHOST $i --lock-tables=false   > ~/.backups/$SCRIPTNAME/db/$i.`date +\%Y-\%m-\%d_\%H-\%M-\%S`.sql
       done

       #if running in -v mode(verbose),  give some output
       if [ "$MODE" = "-v" ]; then
       echo "back up your folders to local .backups folder";
       fi

       for i in "${FOLDERS[@]}"
       do
    rsync -a -t -q $MODE  --delete  --links  $i  ~/.backups/$SCRIPTNAME/
       done

       #if running in -v mode(verbose),  give some output
       if [ "$MODE" = "-v" ]; then
       echo "all done!"
       fi