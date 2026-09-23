# Mart

E-commerce storefront and admin panel built with Laravel and Filament.

A two-app setup: a customer-facing storefront and a separate API/admin service that owns the catalogue,
orders and site settings.

## What it does

- **Catalogue** — products, categories and customer reviews
- **Cart and checkout** — cart items through to placed orders with line items
- **Admin panel** — Filament resources for products, categories and orders
- **Site settings** — editable store configuration (branding, contact details) from the admin panel
- **Accounts** — customer registration and authentication via Laravel Sanctum

## Stack

| Layer | Technology |
|---|---|
| Storefront | Laravel, Blade |
| API + admin | Laravel, Filament, Sanctum |
| Database | MySQL |

## Repo layout

```
frontend/   Laravel storefront            → http://127.0.0.1:8000
backend/    Laravel API + Filament admin  → http://127.0.0.1:8001/admin
```

## Running locally

```bash
# API + admin
cd backend
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan serve --port=8001

# Storefront
cd ../frontend
composer install
cp .env.example .env && php artisan key:generate
php artisan serve --port=8000
```

The admin panel lives at `http://127.0.0.1:8001/admin`, and site settings at
`http://127.0.0.1:8001/admin/site-settings`.
