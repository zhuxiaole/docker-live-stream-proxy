#!/usr/bin/env sh
apk --no-cache add curl
curl --silent --fail http://app:8080/fpm-ping
