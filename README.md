# Manila FAME — Backend (Laravel)

This document explains how to set up and run the Laravel backend for the Manila FAME registration SPA.

## Requirements
- PHP 8.1+ (matching the project's composer.json)
- Composer
- MySQL (or compatible) database
- Node.js and npm (for frontend)

## Environment (.env)
1. Copy the example env file:

```bash
cp .env.example .env
```

2. Edit `.env` and set your database and other values. Important variables:

- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `APP_URL` (e.g. `http://localhost:8000`)

3. Generate the app key:

```bash
php artisan key:generate
```

## Install backend dependencies

```bash
composer install --no-interaction --prefer-dist
```

## Database migrations & storage
Run the migrations to create the necessary tables:

```bash
php artisan migrate
```

Create the public storage symlink so uploaded brochures are accessible (optional for local testing):

```bash
php artisan storage:link
```

## Run the backend server

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

This starts the API at `http://127.0.0.1:8000` by default.

## Frontend (Vue) quick start
From the `frontend/` folder:

1. Copy or update environment variables:

```bash
cp frontend/.env.example frontend/.env
# Edit frontend/.env and set VITE_API_BASE to your backend URL, e.g. http://127.0.0.1:8000
```

2. Install and run:

```bash
cd frontend
npm install
npm run dev
```

The Vite dev server typically runs at `http://localhost:5173` — ensure `VITE_API_BASE` points to the backend `APP_URL`.

## Notes & Common Issues
- CORS: If the frontend runs on a different origin, enable CORS in your Laravel app (install/enable `fruitcake/laravel-cors` or configure `app/Http/Middleware/` appropriately).
- File uploads: Uploaded brochures are stored in `storage/app/public/brochures` and the path is saved to the `companies` table. Use `php artisan storage:link` to serve them via `public/storage`.
- Environment differences: For production, set proper `APP_URL`, `DB_*` credentials, and secure filesystem/storage settings.

## Useful commands
- Run migrations: `php artisan migrate`
- Rollback last migration: `php artisan migrate:rollback`
- Run tests: `vendor/bin/phpunit` (if tests are present)

If you want, I can also add a README in the frontend with explicit `VITE_API_BASE` instructions and a sample `.env` file.