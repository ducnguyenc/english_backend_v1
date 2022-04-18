!/bin/bash
docker-compose up
docker-compose exec app bash
composer install
cp .env.example .env
php artisan key:gen