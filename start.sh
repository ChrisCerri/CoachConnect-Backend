#!/bin/sh

# Attendi che il database sia pronto (se usi MySQL/Postgres)
# Puoi personalizzare host e porta
echo "Attendo che il DB sia disponibile..."
while ! nc -z db 3306; do
  sleep 1
done

echo "DB disponibile, eseguo le migrazioni..."
php artisan migrate --force

echo "Avvio PHP-FPM..."
php-fpm
