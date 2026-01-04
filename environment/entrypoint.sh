#!/bin/sh

set -e

chmod -R 777 /var/www/api/storage
chmod -R 777 /var/www/api/bootstrap/cache
chmod -R 777 /var/www/api/database
# touch /var/www/api/database/database.sqlite
# chmod -R 666 /var/www/api/database/database.sqlite
# chown -R nginx:nginx /var/cache
# chown -R nginx:nginx /var/log


php-fpm &
nginx -g 'daemon off;'