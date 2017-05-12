FROM php:7-apache

RUN apt-get update \
  && apt-get install -y libmcrypt-dev netcat \
  && docker-php-ext-install pdo_mysql mysqli mbstring gd iconv mcrypt opcache \
  && a2enmod rewrite

COPY docker/run.sh /run.sh
RUN chmod u+rwx /run.sh

RUN chown -R root:www-data /var/www

ENTRYPOINT [ "/run.sh" ]
CMD ["apache2-foreground"]