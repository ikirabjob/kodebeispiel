#!/bin/bash
composer install
#bash /docker-scripts/wait-for-it.sh -t 120 db:5432

# run migrations
php bin/console d:m:m -n

# clear cache
php bin/console c:c

# generate the SSL keys for JWT
php bin/console lexik:jwt:generate-keypair -n --skip-if-exists

#bash /docker-scripts/wait-for-it.sh -t 120 127.0.0.1:9000
## run webserver
#PHP_CLI_SERVER_WORKERS=10 php -S 0.0.0.0:80 -t public

echo "starting php-fpm"
exec php-fpm -F