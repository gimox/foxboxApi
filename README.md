FOXBOX API WEB SERVICE
================================

Add custom WebService for FoxBox device



DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

Foxbox
Tested with FoxBox GT



INSTALLATION
------------


### Install via Git

https://github.com/gimox/foxboApi.git
php composer.phar update



### FOXBOX  configuration

Enable MOD-REWRITE
1) connect in ssh to foxbox: see http://www.smsfoxbox.it/documents/pdf/FoxBox-G25_Quick-Start-Guide.pdf
2) copy the project folder to /mnt/flash/ named foxboxApi (the name must be foxbApi with camelcase) so now you have /mnt/flash/foxboxApi
3) copy from projectdirectory/scripts/foxboxApi to /etc/apache2/site-available
4) enable mod rewrite: sudo a2enmod rewrite 
5) enable new virtualhost: a2ensite foxboxApi
7) add right  assets: chown -R www-data:www-data /mnt/flash/foxboxApi/web/assets
6) add right to runtime:  chown -R www-data:www-data /mnt/flash/foxboxApi/runtime
5) sudo service apache2 restart


SSL Version
replace with:
3) copy from projectdirectory/scripts/foxboxApiSSL to /etc/apache2/site-available
5) enable new virtualhost: a2ensite foxboxApiSSL


to test it go to:
http://foxboxip:8081
SSL
https://foxboxip:8082



IMPORTANT INFO
--------------
For security reason, Foxbox can't be in WWW. It must be under firewall and accessible only by application server in Https.

this version is not in release yet, please don't use it now.
