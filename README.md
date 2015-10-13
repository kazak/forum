Dolly - the pizza story
=====

Installation:
====
git clone <...><br>
cd <...><br>

# Assuming you have composer installed globally
1. install dependencies<br>
composer install

2. create database<br>
php app/console doctrine:database:create

3. copy fonts from bootstrap/bootswatch to web/fonts<br>
php app/console akuma:bootswatch:install

4. configure project assets (images, js, css, svg etc)<br>
php app/console assets:install --symlink

5. compiling js and css with use of assetic - we have configuration for assetic in assetic.yml<br>
php app/console assetic:dump

6. run migrations to create tables in DB<br>
php app/console doctrine:migrations:migrate

7. create mock records in DB<br>
php app/console doctrine:fixtures:load

8. Create symbolic link in .git/hooks directory on pre-commit.sh in root directory.
ln -s ../../hooks/pre-commit.sh .git/hooks/pre-commit   
ln -s ../../hooks/post-merge.sh .git/hooks/post-merge   


Hints:
====
If you need your changes on dev server - you need to create merge request to master branch.<br>
After merge, Jenkins will pull changes, run specific targets and deploy everything to dev server.<br>

Each time we do deploy we rebuild our tables with new migrations, load fixtures (everything in db will be purged) and run unit tests.<br>
So if you need for example for your unit tests something special in db - create Fixture with right priority for load.<br>


Translations:
====

1) Setting URL:
    a) add to the file 'app / config / parametra.yml' in the "locales" locale
    b) in сonsole run the command -
    'php app / console translation: extract' your locale '--enable-extractor = jms_i18n_routing --bundle =' required bundle '--output-format = yml'
     - which sgeniriruet 3 file - with routing with adjustments and transfers (for static text)
2) Translation adjustment for the content of the database file 'app / config / config_dev.yml'
and 'app / config / config_prod.yml' in "app_core" indicate the inclusion of transfers 'translations_enabled'


WebSocket:
===

Apache settings:  
- Install and enable next modules for apache: mod_proxy, mod_proxy_wstunnel  
- Add next configuration to Virtualhost conf file:  
<Location /SOME_URL>  
    ProxyPass "ws://localhost:9090/"  
</Location>  
SOME_URL - string value, could be some special route, e.g. /exchange or /my/lovely/socket  

Symfony settings:  
- We are using Symfony Bundle: gos/web-socket-bundle . This bundle is based on: Ratchet and AuthobahnJS  
- How to use this bundle read please here: https://github.com/GeniusesOfSymfony/WebSocketBundle  

By default in our project were added default settings for use of this bundle. Check config.yml section gos_web_socket, check pubsub/routing.yml in appcore bundle, check services.yml in appcore. Also was added basic RPC service here App\CoreBundle\Service\RPC . And added basic test js code to connect to our socket in app_dolly pagelayout.html.tpl (footer_js section).  

To run websocket, exec this command in project root: php app/console gos:websocket:server  
you will see next message in console:  
[2015-09-16 15:35:27] websocket.INFO: Starting web socket    
[2015-09-16 15:35:27] websocket.INFO: Launching Ratchet on 127.0.0.1:9090 PID: 97364  

To stop websocket server double click ctrl+C .  

Local install, you could use direct connect to configured ip and port without configuring apache. But on server we need configured web server to not expose web socket port.   


Memcached
===

- Run next command to install extensions:   
sudo apt-get install php5-memcache memcached   

- To check if it was installed and memcached enabled as extensions, run next commands:   
php -i|grep memcache   

You should get something like this:   
/etc/php5/cli/conf.d/20-memcache.ini,     
memcache  
memcache support => enabled  

Also you can run next command to check process:   
ps aux | grep memcache   
Result should be next:   
memcache 120784  0.0  0.0 325400  2624 ?        Sl   14:08   0:00 /usr/bin/memcached -m 64 -p 11211 -u memcache -l 127.0.0.1   
Value right after -p means that memcached is running on the port 11211   

Also we can check settings by running next command:   
echo "stats settings" | nc localhost 11211   

You will get something like this:   
STAT maxbytes 67108864   
STAT maxconns 1024   
STAT tcpport 11211   
STAT udpport 11211   
STAT inter 127.0.0.1   
STAT verbosity 0   
STAT oldest 0   
STAT evictions on   
STAT domain_socket NULL   
STAT umask 700   
STAT growth_factor 1.25   
STAT chunk_size 48   
STAT num_threads 4   
STAT num_threads_per_udp 4   
STAT stat_key_prefix :   
STAT detail_enabled no   
STAT reqs_per_event 20   
STAT cas_enabled yes   
STAT tcp_backlog 1024   
STAT binding_protocol auto-negotiate   
STAT auth_enabled_sasl no   
STAT item_size_max 1048576   
STAT maxconns_fast no   
STAT hashpower_init 0   
STAT slab_reassign no   
STAT slab_automove 0   
END   

- Run:
composer update && sudo service apache2 restart

- Memcached was installed as a service so you could start, stop, restart it as usual service:   
sudo service memcached restart   

- Check this article for more details on how-to install and use memcached: https://www.digitalocean.com/community/tutorials/how-to-install-and-use-memcache-on-ubuntu-14-04   
*Note: we will use php5-memcache not php5-memcached as in article.*   

- To simplify implementation process we will use this bundle https://github.com/LeaseWeb/LswMemcacheBundle for work with Memcached.   

- All configuration was added only for *prod* environment.


=======
SiteMap

Command to generate - php app/console app:generate:site_map
must be set this command in cronjob to every night