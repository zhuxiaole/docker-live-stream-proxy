#!/usr/bin/env sh
docker-compose logs -f app
apk --no-cache add curl
curl --silent --fail http://app:8080/fpm-ping
