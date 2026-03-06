#!/bin/sh
set -e

echo "Starting Symfony container..."

if [ ! -d "vendor" ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction --prefer-dist
fi

echo "Waiting for database..."

until php -r "new PDO('mysql:host=db;dbname=symfony_task_manager', 'symfony', 'symfony');" 2>/dev/null; do
  sleep 2
done

echo "Database ready"

php bin/console doctrine:migrations:migrate --no-interaction

if [ ! -f ".docker-initialized" ]; then
    echo "First container initialization..."

    php bin/console lexik:jwt:generate-keypair --skip-if-exists --no-interaction
    php bin/console app:user:import-from-json-placeholder
    php bin/console app:user:configure-password sincere@april.biz Admin123456
    php bin/console app:user:configure-password shanna@melissa.tv User12345678
    php bin/console app:user:configure-roles sincere@april.biz ROLE_ADMIN

    touch .docker-initialized
else
    echo "Container already initialized"
fi

exec "$@"
