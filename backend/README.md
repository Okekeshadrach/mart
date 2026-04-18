# MART Backend

Laravel 11 + MySQL API for the MART multi-product e-commerce storefront.

The static frontend lives in [`frontend/`](../frontend) and the Laravel API lives in [`backend/`](.).
The API responses are formatted in camelCase where it helps match the existing frontend product/cart shape.

## Stack

- Laravel 11
- PHP 8.3
- MySQL
- Laravel Sanctum token auth
- Filament admin panel
- Form Requests for validation
- API Resources for JSON formatting
- Policy + middleware protection for admin product CRUD

## Project Layout

- `app/Models`
  - `User.php`
  - `Category.php`
  - `Product.php`
  - `Order.php`
  - `OrderItem.php`
  - `CartItem.php`
  - `Review.php`
- `app/Http/Controllers/Api`
  - `AuthController.php`
  - `CategoryController.php`
  - `ProductController.php`
  - `CartController.php`
  - `OrderController.php`
  - `ReviewController.php`
  - `Admin/ProductController.php`
- `app/Http/Requests`
  - `Auth/RegisterRequest.php`
  - `Auth/LoginRequest.php`
  - `ProductIndexRequest.php`
  - `Admin/StoreProductRequest.php`
  - `Admin/UpdateProductRequest.php`
  - `StoreCartItemRequest.php`
  - `UpdateCartItemRequest.php`
  - `StoreOrderRequest.php`
  - `StoreReviewRequest.php`
- `app/Http/Resources`
  - `UserResource.php`
  - `CategoryResource.php`
  - `ProductResource.php`
  - `CartItemResource.php`
  - `OrderItemResource.php`
  - `OrderResource.php`
  - `ReviewResource.php`
  - `SiteSettingResource.php`
- `app/Filament/Pages`
  - `SiteSettings.php`
- `database/migrations`
  - `0001_01_01_000000_create_users_table.php`
  - `2026_04_18_171208_create_personal_access_tokens_table.php`
  - `2026_04_18_180000_create_categories_table.php`
  - `2026_04_18_180100_create_products_table.php`
  - `2026_04_18_180200_create_orders_table.php`
  - `2026_04_18_180300_create_order_items_table.php`
  - `2026_04_18_180400_create_cart_items_table.php`
  - `2026_04_18_180500_create_reviews_table.php`
  - `2026_04_18_190000_create_site_settings_table.php`
- `routes/api.php`

## Environment

Update `.env` if your MySQL credentials differ from the defaults:

```env
APP_NAME=MART
APP_URL=http://127.0.0.1:8001

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mart
DB_USERNAME=root
DB_PASSWORD=

FRONTEND_URL=http://127.0.0.1:8000
CORS_ALLOWED_ORIGINS=http://127.0.0.1:8000,http://localhost:8000
```

For local development, keep `CORS_ALLOWED_ORIGINS` aligned with the frontend port. Tighten it further in production if needed.

## Setup

1. Create the database in MySQL:

```sql
CREATE DATABASE mart;
```

2. Install dependencies if needed:

```bash
composer install
```

3. Generate the app key if needed:

```bash
php artisan key:generate
```

4. Run migrations and seed the sample MART catalog:

```bash
php artisan migrate --seed
```

5. Start the API:

```bash
php artisan serve --host=127.0.0.1 --port=8001
```

The API will be available at `http://127.0.0.1:8001/api`.

## Seeded Accounts

- Admin: `admin@mart.test` / `password`
- Customer: `customer@mart.test` / `password`

The seeder also creates enough sample customer accounts and reviews to match the existing eight product review counts from the frontend.

## Filament Admin

- URL: `http://127.0.0.1:8001/admin`
- Login with the seeded admin account: `admin@mart.test` / `password`
- Site settings page: `http://127.0.0.1:8001/admin/site-settings`

The Filament panel is installed in the backend app and is restricted to users with the `admin` role.

## Artisan Commands

Scaffolding and setup commands used for this backend:

```bash
php artisan install:api --without-migration-prompt --no-interaction
php artisan key:generate
php artisan migrate --seed
php artisan route:list
php artisan serve
php artisan migrate:fresh --seed
```

## Authentication

Auth uses Sanctum personal access tokens.

Example login request:

```http
POST /api/login
Content-Type: application/json

{
  "email": "customer@mart.test",
  "password": "password"
}
```

Example authenticated request header:

```http
Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json
```

## API Routes

Public routes:

- `POST /api/register`
- `POST /api/login`
- `GET /api/settings/site`
- `GET /api/products`
- `GET /api/products/{slug}`
- `GET /api/categories`

Authenticated routes:

- `POST /api/logout`
- `GET /api/cart`
- `POST /api/cart`
- `PATCH /api/cart/{id}`
- `DELETE /api/cart/{id}`
- `POST /api/orders`
- `GET /api/orders`
- `GET /api/orders/{id}`
- `POST /api/products/{id}/reviews`

Admin routes:

- `GET /api/admin/products`
- `POST /api/admin/products`
- `GET /api/admin/products/{id}`
- `PUT/PATCH /api/admin/products/{id}`
- `DELETE /api/admin/products/{id}`

## Product Filters

`GET /api/products` supports:

- `category`
  - Accepts category name, slug, or a comma-separated list.
- `min_rating`
- `search`
  - Searches product name, description, and category name.
- `sort`
  - Supported values: `featured`, `price-asc`, `price_asc`, `price-desc`, `price_desc`, `newest`

Example:

```http
GET /api/products?category=electronics&min_rating=4&search=watch&sort=price-asc
```

## Order Payload

`POST /api/orders` accepts either a nested `shipping_address` object or the flat frontend fields.

Example:

```json
{
  "payment_method": "card",
  "shipping_address": {
    "first_name": "Ada",
    "last_name": "Okafor",
    "email": "ada@example.com",
    "address": "42 Marina Road",
    "city": "Lagos",
    "zip": "100001",
    "country": "Nigeria"
  }
}
```

The existing frontend-style payload also works:

```json
{
  "first_name": "Ada",
  "last_name": "Okafor",
  "email": "ada@example.com",
  "address": "42 Marina Road",
  "city": "Lagos",
  "zip": "100001",
  "country": "Nigeria",
  "payment": "card"
}
```

## Notes

- Product detail routes use slugs publicly: `GET /api/products/{slug}`.
- Review posting uses product IDs: `POST /api/products/{id}/reviews`.
- Public storefront settings are exposed at `GET /api/settings/site` and managed through Filament.
- Cart, order, and review routes are protected with `auth:sanctum`.
- Admin product routes are protected with both `auth:sanctum` and the `admin` middleware, with `ProductPolicy` enforcing authorization in the controller layer.
- Validation errors return standard Laravel JSON `422` responses.
- Auth failures and missing API routes/resources return JSON errors from `bootstrap/app.php`.
