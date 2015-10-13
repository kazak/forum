#!/bin/bash

countStaged=$(git status --porcelain 2>/dev/null| grep "^[M|A]" | wc -l)
if [ $countStaged -gt 0 ]
then
  echo "Set default restaurant times ..."
  php app/console restaurant:times:default:setter
  echo "Run migrations..."
  php app/console doctrine:migrations:migrate
  echo "Load fixtures..."
  php app/console doctrine:fixtures:load
  echo "Running clear cache..."
  php app/console cache:clear --env=prod --no-debug
  php app/console cache:clear

  filesForMD=$(git status --porcelain 2>/dev/null| grep "^[M|A]" | grep "\.php$" | grep "src" | sed 's/. src/,src/g'|sed 's/ //g'|sed 's/^[M|A]//g')
  filesForMD=$(echo $filesForMD | sed 's/^,//g'|sed 's/ //g')

  if [ ! -z "$filesForMD" ]
  then
    echo "Running phpmd..."
    phpMDResult=$(bin/phpmd $filesForMD text app/phpmd.xml)
    if [ ! -z "$phpMDResult" ]
    then
      echo "$phpMDResult"
    else
      echo "phpMD is OK with files: $filesForMD"
    fi
  fi
fi