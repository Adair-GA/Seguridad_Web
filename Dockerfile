FROM php:7.2.2-apache
COPY security.conf /etc/apache2/conf-enabled
RUN cd /etc/apache2/mods-available
RUN a2enmod headers
#RUN systemctl restart apache2
RUN docker-php-ext-install mysqli
