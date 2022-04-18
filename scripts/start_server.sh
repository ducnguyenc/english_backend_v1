!/bin/bash
docker-compose up
docker-compose exec app bash
compose install
cp .env.example .env
php artisan key:gen