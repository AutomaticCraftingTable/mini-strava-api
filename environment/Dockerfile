FROM richarvey/nginx-php-fpm:3.1.6

COPY ./environment/nginx/default.conf /etc/nginx/sites-enabled/default.conf

ENTRYPOINT [ "/workspace/environment/entrypoint.sh" ]