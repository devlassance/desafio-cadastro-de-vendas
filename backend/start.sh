#!/usr/bin/env sh
set -e

wait_for_db() {
  host="${DB_HOST:-db}"
  user="${DB_USERNAME:-root}"
  pass="${DB_PASSWORD:-root}"
  echo "Waiting for MySQL at $host ..."
  for i in $(seq 1 60); do
    if mysql --ssl-mode=DISABLED -h "$host" -u "$user" -p"$pass" -e "SELECT 1" >/dev/null 2>&1; then
      echo "MySQL is up."
      return 0
    fi
    sleep 1
  done
  echo "MySQL not ready after waiting. Continuing anyway..."
}

# Ensure DB exists (for safety if MYSQL_DATABASE isn't provisioned)
if [ -n "${DB_DATABASE:-}" ]; then
  wait_for_db
  echo "Ensuring database '$DB_DATABASE' exists..."
  mysql --ssl-mode=DISABLED -h "${DB_HOST:-db}" -u "${DB_USERNAME:-root}" -p"${DB_PASSWORD:-root}" -e "CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" || true
fi

# Install dependencies if vendor is missing
if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist || true
fi

php artisan key:generate || true
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true

# If DB settings are present, wait for DB before running any DB-related artisan commands
if [ -n "${DB_DATABASE:-}" ]; then
  wait_for_db
fi

# Ensure database-backed features have their tables
if grep -q '^SESSION_DRIVER=database' .env; then
  # create migration for sessions table if not present
  if ! ls database/migrations/*_create_sessions_table.php >/dev/null 2>&1; then
    php artisan session:table || true
  fi
fi

if grep -q '^CACHE_STORE=database' .env; then
  # Laravel 12 includes cache table migration by default; if missing, offer fallback
  if ! ls database/migrations/*_create_cache_table.php >/dev/null 2>&1; then
    php artisan cache:table || true
  fi
fi

if grep -q '^QUEUE_CONNECTION=database' .env; then
  if ! ls database/migrations/*_create_jobs_table.php >/dev/null 2>&1; then
    php artisan queue:table || true
  fi
fi

# Run migrations
php artisan migrate --force || true

# Start PHP-FPM
exec php-fpm
