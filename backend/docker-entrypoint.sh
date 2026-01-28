#!/usr/bin/env sh
set -e

# If .env does not exist but .env.example does, copy it
if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

# Generate app key if not set
if [ -f artisan ]; then
  if ! php artisan key:generate --ansi --no-interaction >/dev/null 2>&1; then
    php artisan key:generate --ansi || true
  fi

  # Run migrations (force true for non-interactive environments)
  php artisan migrate --force || true

  # Create storage symlink if missing
  php artisan storage:link || true
fi

# Default port fallbacks to 8000 if not provided by environment (Render provides $PORT)
PORT=${PORT:-8000}

echo "Starting Laravel dev server on 0.0.0.0:${PORT}"
exec php artisan serve --host=0.0.0.0 --port=${PORT}
