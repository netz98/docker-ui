FROM phusion/baseimage:0.9.15

ENV HOME /root

# https://github.com/phusion/baseimage-docker#disabling_ssh
RUN rm -rf /etc/service/sshd /etc/my_init.d/00_regen_ssh_host_keys.sh

CMD ["/sbin/my_init"]

# Nginx-PHP Installation
RUN apt-get update
RUN apt-get install -y vim curl wget build-essential python-software-properties
RUN add-apt-repository -y ppa:ondrej/php5
RUN add-apt-repository -y ppa:nginx/stable
RUN apt-get update
RUN apt-get install -y php5-cli php5-fpm php5-curl php5-gd php5-mcrypt

RUN sed -i "s/;date.timezone =.*/date.timezone = UTC/" /etc/php5/fpm/php.ini
RUN sed -i "s/;date.timezone =.*/date.timezone = UTC/" /etc/php5/cli/php.ini

RUN apt-get install -y nginx

RUN echo "daemon off;" >> /etc/nginx/nginx.conf
RUN sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php5/fpm/php-fpm.conf
RUN sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php5/fpm/php.ini

RUN mkdir               /var/www
ADD srvconfig/default   /etc/nginx/sites-available/default
RUN mkdir               /etc/service/nginx
ADD srvconfig/nginx.sh  /etc/service/nginx/run
RUN chmod 700           /etc/service/nginx/run
RUN mkdir               /etc/service/phpfpm
ADD srvconfig/phpfpm.sh /etc/service/phpfpm/run
RUN chmod 700           /etc/service/phpfpm/run

ADD src /var/www/src
ADD web /var/www/web
WORKDIR /var/www
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install -vvv

EXPOSE 8090

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*