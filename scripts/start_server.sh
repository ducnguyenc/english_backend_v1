#!/bin/bash
# service httpd start
docker-composer up -d
docker-composer exec app bash
php artisan serve