#!/bin/bash

echo "Updating composer..."
composer update
echo "Run migrations..."
php app/console doctrine:migrations:migrate
echo "Load fixtures..."
php app/console doctrine:fixtures:load
echo "Running clear cache..."
php app/console cache:clear --env=prod --no-debug
php app/console cache:clear