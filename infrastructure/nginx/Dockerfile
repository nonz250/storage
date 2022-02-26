FROM openresty/openresty:1.19.3.1-alpine

WORKDIR /var/www/app

COPY ./infrastructure/nginx/nginx.conf /usr/local/openresty/nginx/conf/nginx.conf
COPY ./infrastructure/nginx/conf.d /etc/nginx/conf.d
