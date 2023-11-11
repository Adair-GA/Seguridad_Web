FROM php:7.2.2-apache
COPY security.conf /etc/apache2/conf-enabled
RUN docker-php-ext-install mysqli
