FROM php:8.2-apache

# rewrite + headers utiles (redirections, .htaccess, sécurité)
RUN a2enmod rewrite headers

# Bloque l'accès HTTP au dossier data/ (content.json, .admin_password)
COPY docker/deny-data.conf /etc/apache2/conf-available/deny-data.conf
RUN a2enconf deny-data

# Code du site
COPY . /var/www/html/

# Dossiers persistants (montés en volume dans docker-compose)
RUN mkdir -p /var/www/html/data /var/www/html/uploads \
 && chown -R www-data:www-data /var/www/html/data /var/www/html/uploads

EXPOSE 80
