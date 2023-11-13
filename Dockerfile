FROM php:8.2.12-apache
#RUN cd /etc/apache2/mods-available
RUN docker-php-ext-install mysqli
RUN a2enmod headers
RUN service apache2 restart
COPY security.conf /etc/apache2/conf-enabled
COPY apache2.conf /etc/apache2/
