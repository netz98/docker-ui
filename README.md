# Docker UI

Simple web interface which lists all local available docker containers
and offers a button to start/stop it.

## Requirements

- >= PHP 5.4
- Composer
- Bower

## Installation

    bower install
    composer.phar install

## Container Groups

All containers are grouped by name.
The delimiters to group containers are the "_" or "-" character which can be found in the
container name.

## VHost
    
Add vhost to "web" folder. A sample readme for Apache with enabled mod_rewrite is available.    
