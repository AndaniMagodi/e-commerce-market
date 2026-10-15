# E-Commerce Market - storefront and admin dashboard

A Laravel/MySQL assessment project by Andani Magodi. Visitors browse categories, search the catalogue and open product details. Administrators manage products, images, prices, stock and publication status through a protected dashboard.

The initial product/data flow was built step by step by Andani. AI assistance was used to complete CRUD, styling, upload handling, tests and documentation.

## Requirements

- PHP 8.4.1+
- Composer 2
- MySQL 8, with an empty database and a user that can run migrations
- PHP extensions required by Laravel, plus PDO MySQL, GD with WebP support and Fileinfo. Tests also need PDO SQLite

No Node build is needed. The app serves its own CSS and JavaScript from `public`. Catalogue photographs are stored locally; credits are in [IMAGE_CREDITS.md](IMAGE_CREDITS.md).

## Install

```sh
git clone https://github.com/AndaniMagodi/e-commerce-market.git
cd e-commerce-market
composer install
cp .env.example .env
php artisan key:generate
```

On Windows Command Prompt, use `copy .env.example .env` instead of `cp`.

Create a MySQL database and user, then set these values in `.env` (use your own credentials):

```dotenv
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=afrimart_assessment
DB_USERNAME=your_application_user
DB_PASSWORD=your_local_password
```

Then run:

```sh
php artisan migrate --seed
php artisan storage:link
php artisan app:create-admin
php artisan serve --host=127.0.0.1 --port=8000
```

`app:create-admin` asks for a name, email and password (at least 12 characters, hidden input). No default admin password is committed.

Open `http://127.0.0.1:8000` for the shop, `/login` to sign in and `/admin` for the dashboard. Products are managed at `/admin/products`.

## Features

- Eight sample products in four categories, each with a photo; two are out of stock.
- Search by name and description; category tiles filter the same catalogue.
- Product details page. Hidden or missing products return 404.
- Admin login and logout. Admin routes are restricted to administrators.
- Dashboard counts: total, active and out-of-stock products, and categories.
- Add, edit and delete products, with image upload, price, category, quantity and availability. Changes show on the storefront on the next page load.
- Responsive CSS with breakpoints for narrow screens.

## Project map

- `routes/web.php`: public, login and protected admin routes
- `app/Http/Controllers/StorefrontController.php`: public catalogue and details
- `app/Http/Controllers/Admin/ProductController.php`: product CRUD and image handling
- `app/Http/Requests/Admin/ProductRequest.php`: product validation and authorization
- `app/Services/ProductImageStorage.php`: image decoding, WebP encoding and cleanup
- `app/Http/Middleware/EnsureUserIsAdmin.php`: admin authorization
- `app/Console/Commands/CreateAdmin.php`: admin account setup
- `resources/views`: storefront and admin views
- `public/css/app.css`, `public/js/app.js`: styles and small UI scripts
- `tests/Feature`: HTTP, CRUD, upload, authentication and access tests

## Tests

```sh
php artisan test --compact
```

Tests use in-memory SQLite and fake upload storage, so your MySQL data is not touched.

## Screenshots

See the `screenshots/` folder.

## Security

See the [security explanation](SECURITY.md) for how SQL injection, XSS, CSRF, password hashing, validation, image uploads, authorization and credentials are handled.
