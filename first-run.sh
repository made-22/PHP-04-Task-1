#!/bin/bash

# build
#docker-compose build

# run containers
#docker-compose up -d

# create databases
# main DB
docker-compose exec db psql -U postgres -tc "SELECT 1 FROM pg_database WHERE datname = 'short_links_db'" | grep -q 1 || psql -U postgres -c "CREATE DATABASE short_links_db"
# testing DB
docker-compose exec db psql -U postgres -tc "SELECT 1 FROM pg_database WHERE datname = 'short_links_testing_db'" | grep -q 1 || psql -U postgres -c "CREATE DATABASE short_links_testing_db"

# install laravel
docker-compose exec app composer install
docker-compose exec app php artisan storage:link
docker-compose exec app php artisan migrate:fresh --seed

