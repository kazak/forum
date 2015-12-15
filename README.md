1. create database<br>
php app/console doctrine:database:create

2. copy fonts from bootstrap/bootswatch to web/fonts<br>
php app/console akuma:bootswatch:install

3. configure project assets (images, js, css, svg etc)<br>
php app/console assets:install --symlink

4. compiling js and css with use of assetic - we have configuration for assetic in assetic.yml<br>
php app/console assetic:dump

5. run migrations to create tables in DB<br>
php app/console doctrine:migrations:migrate

6. create mock records in DB<br>
php app/console doctrine:fixtures:load

7. php app/console sonata:seo:sitemap web url


++++++++++
icons - https://fortawesome.github.io/Font-Awesome/icons/
