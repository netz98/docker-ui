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
    
## Installation via Docker

Requires preinstalled and working Docker and depending on your OS: boot2docker.

	docker build -t n98/dockerui github.com/netz98/docker-ui

After the successful build run:
	
	docker run -d -p 8090:8090 -v /var/run/docker.sock:/var/run/docker.sock n98/dockerui
	
If you have boot2docker installed get the IP address of the VM and then point your browser to:

	http://<ip>:8090
