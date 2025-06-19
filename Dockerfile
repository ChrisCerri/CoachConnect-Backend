# Usa la base PHP-FPM come stage iniziale
FROM php:8.2-fpm-alpine AS php_fpm_builder

# Installa le dipendenze di sistema e le estensioni PHP
RUN apk add --no-cache \
    git \
    curl \
    libzip-dev \
    nginx \
    && docker-php-ext-install pdo_mysql zip opcache

# Copia Composer dalla sua immagine ufficiale
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Imposta la directory di lavoro all'interno del container
WORKDIR /var/www/html

# Copia i file dell'applicazione (questo include anche composer.json/lock)
COPY . .

# Installa le dipendenze di Composer
# Le dipendenze di dev (--no-dev) non sono necessarie in produzione
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Imposta i permessi per le directory storage e bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Rimuovi la configurazione Nginx predefinita (se esiste)
RUN rm -f /etc/nginx/conf.d/default.conf

# Copia la tua configurazione Nginx personalizzata
# Assicurati che il tuo nginx.conf sia nella stessa directory del Dockerfile
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Espone la porta 80 che Nginx userà
EXPOSE 80

# Crea uno script di avvio che avvierà sia PHP-FPM che Nginx
RUN echo '#!/bin/sh' > /usr/local/bin/start.sh \
    && echo 'php-fpm' >> /usr/local/bin/start.sh \
    && echo 'nginx -g "daemon off;"' >> /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Il comando predefinito all'avvio del container
CMD ["start.sh"]