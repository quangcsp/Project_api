# fbook_api
Fbook - for sharing and discussing about book

## Required

 - Git
 - Composer
 - PHP v.7.x
 - Mysql v.5.7.x
 - Node
 - Npm

## Setup

- git clone git@github.com:framgia/fbook_api.git
- composer install --no-scripts
- cp .env.example .env
- php artisan passport:install
- php artisan key:generate
- php artisan migrate
- php artisan db:seed

## Configs

**Creating A Password Grant Client**

`php artisan passport:client --password`

Config API_CLIENT_SECRET and API_CLIENT_id in .env

## Testing
**Prepare database**
- php artisan migrate --database=mysql_test
- php artisan db:seed --database=mysql_test

**Run**
```
$ ./vendor/bin/phpunit
```
