#!/bin/sh

# Wait, until mysql container is ready
while ! nc -z mysql 3306; do sleep 3; done

# Run migration
php /var/www/yii migrate/up --interactive=0

# Execute provided command
exec "$@"