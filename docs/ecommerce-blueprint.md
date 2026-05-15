# Northstar Commerce Blueprint

Laravel-first eCommerce application built in `C:\Taskmanagement\task-app` using:

- Laravel 12
- Blade SSR views
- Vite + Axios for client interactions
- Sanctum for API authentication
- MySQL-ready schema

## Folder structure

```text
task-app/
|-- app/
|   |-- Http/Controllers/
|   |   |-- Admin/
|   |   |-- Api/
|   |   |-- AccountController.php
|   |   |-- AuthController.php
|   |   |-- CartController.php
|   |   |-- CheckoutController.php
|   |   |-- StorefrontController.php
|   |   `-- WishlistController.php
|   |-- Models/
|   |-- Notifications/
|   `-- Services/
|-- database/
|   |-- migrations/
|   `-- seeders/
|-- docs/
|   `-- ecommerce-blueprint.md
|-- resources/
|   |-- css/app.css
|   |-- js/app.js
|   `-- views/
|       |-- account/
|       |-- admin/
|       |-- auth/
|       |-- docs/
|       |-- layouts/
|       `-- store/
|-- routes/
|   |-- api.php
|   `-- web.php
`-- composer.json
```

## Database schema

Core tables:

- `users`: role, status, remember token, OTP preferences, 2FA flag, last login metadata
- `otp_challenges`: login and 2FA challenge codes with expiry
- `login_alerts`: audit trail for sign-in notifications
- `categories`: nested-ready category catalog
- `products`: SEO fields, badge text, delivery ETA, search keywords
- `product_images`
- `product_variants`
- `carts`
- `cart_items`
- `addresses`
- `orders`
- `order_items`
- `reviews`
- `wishlist_items`
- `banners`
- `coupons`
- `personal_access_tokens`

## Key routes

Web storefront:

- `GET /`
- `GET /shop`
- `GET /products/{slug}`
- `GET /search/suggestions`
- `GET /cart`
- `GET /checkout`
- `GET /account`

Authentication:

- `GET|POST /login`
- `GET|POST /register`
- `GET|POST /otp/login`
- `GET|POST /otp/verify`
- `GET|POST /2fa/challenge`
- `GET /password/forgot`
- `POST /password/email`
- `GET /password/reset/{token}`
- `POST /password/reset`

Admin:

- `GET /admin`
- `resource /admin/products`
- `GET|POST|PATCH|DELETE /admin/categories`
- `GET|PATCH /admin/orders`
- `GET|POST|PATCH|DELETE /admin/banners`
- `GET|POST|PATCH|DELETE /admin/coupons`

API:

- `GET /api/v1/home`
- `GET /api/v1/products`
- `GET /api/v1/products/suggestions`
- `POST /api/v1/auth/register`
- `POST /api/v1/auth/login`
- `POST /api/v1/auth/otp/request`
- `POST /api/v1/auth/otp/verify`
- authenticated cart, wishlist, address, checkout, order, review endpoints

## Security flow

- Password hashing via Laravel hashed cast and `Hash`
- OTP login using `otp_challenges`
- Optional 2FA enforced after password login
- Remember me support on session login
- Token-based password reset using Laravel password broker
- Auth rate limiting on web and API login routes
- Login alert notifications via mail notification class
- Device/session management from the account dashboard
- CSRF protection on Blade forms
- Validation on all auth, cart, checkout, and admin forms

## Sample pages

- Home: `resources/views/store/home.blade.php`
- Product: `resources/views/store/product.blade.php`
- Shop listing: `resources/views/store/shop.blade.php`
- Account center: `resources/views/account/dashboard.blade.php`
- Admin dashboard: `resources/views/admin/dashboard.blade.php`

## Setup

1. Configure `.env` for MySQL and mail.
2. Recommended:
   - `SESSION_DRIVER=database`
   - `CACHE_STORE=database`
   - `QUEUE_CONNECTION=database`
3. Run:
   - `php artisan key:generate`
   - `php artisan migrate --seed`
   - `php artisan view:cache`
4. Start local development:
   - `php artisan serve`
   - `npm.cmd run dev`

## Demo credentials

- Admin: `admin@northstar.test`
- Password: `password`

## Note on frontend

The older `frontend/` Next.js folder is no longer the active implementation path for this build. The live application now uses Laravel Blade plus JS libraries inside the Laravel app itself.
