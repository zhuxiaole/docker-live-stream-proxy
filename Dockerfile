FROM ghcr.io/linuxserver/baseimage-alpine:3.17

LABEL Maintainer="Pigxiaole <zhuxiaole@zhuxiaole.org>"
LABEL Description="Docker live stream proxy."

# Install packages and remove default server definition
RUN apk add --no-cache \
  nginx \
  php81 \
  php81-curl \
  php81-json \
  php81-fpm \
  supervisor

# add Seam
COPY --chmod=755 seam /usr/local/seam

# Configure nginx - http
COPY docker-php-nginx/config/nginx.conf /etc/nginx/nginx.conf
# Configure nginx - default server
COPY docker-php-nginx/config/conf.d /etc/nginx/conf.d/

# Configure PHP-FPM
COPY docker-php-nginx/config/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY docker-php-nginx/config/php.ini /etc/php81/conf.d/custom.ini

# Configure supervisord
COPY docker-php-nginx/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Add application
COPY --chown=nobody docker-php-nginx/src/ /var/www/html/
COPY --chown=nobody src/ /var/www/html/

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody:nobody /var/www/html /run /var/lib/nginx /var/log/nginx

# Switch to use a non-root user from here on
USER nobody

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping