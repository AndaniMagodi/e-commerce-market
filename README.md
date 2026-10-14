# E-Commerce Market - storefront and admin dashboard

A Laravel/MySQL assessment project by Andani Magodi. Public visitors browse categories, search a catalogue and open product details. Administrators maintain products, images, prices, stock and publication status through a protected dashboard.

The initial product/data flow was built step by step by Andani. AI assistance was used to complete CRUD, styling, upload handling, tests and documentation. No source code or company assets were copied from any other project.

## Requirements

- PHP 8.3+ compatible with the committed Composer lock (developed with PHP 8.5)
- Composer 2
- MySQL 8, an empty database and a database account with permissions for migrations
- PHP extensions required by Laravel, plus PDO MySQL, GD with WebP support, and Fileinfo

No Node build is needed: this small application serves its own CSS/JavaScript from `public`. Fonts optionally load from Google Fonts, with system fallbacks. Real catalogue photographs are stored locally with sources and licenses in IMAGE_CREDITS.md and the public photo-credits page; uploaded images are raster images re-encoded as WebP.

## Install

```sh
composer install
cp .env.example .env
php artisan key:generate
```

Create a dedicated MySQL database and application user using your database management tool. Set these values in your local `.env` (supply your own credentials):

```dotenv
APP_NAME="E-Commerce Market"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=afrimart_assessment
DB_USERNAME=your_application_user
DB_PASSWORD=your_local_password
```

The author's isolated local database uses port 3307; a normal MySQL setup commonly uses 3306. The database data directory and private credentials are outside this project and are not included in the submission.

```sh
php artisan migrate --seed
php artisan storage:link
php artisan app:create-admin
php artisan serve --host=127.0.0.1 --port=8000
```

The administrator command asks for name, email and a password with hidden input. It requires at least 12 characters and a matching confirmation. No default administrator password is committed. Use this command to create a reviewer account locally or provide credentials separately through an agreed private channel.

Open `/` for the shop, `/login` for login and `/admin` for the dashboard. Product management is `/admin/products`.

## Features and decisions

- Eight sample products in four categories, each with a photograph; two samples are out of stock.
- Search checks name and description. Category tiles filter the same catalogue.
- An active product is published even if stock is zero. Stock is derived from quantity rather than maintained as a second potentially inconsistent flag.
- Products have a required category, decimal price, quantity, description, image path and publication flag.
- Admin product lists include hidden and out-of-stock records, with search, filters and pagination.
- Product creation requires an image. Editing keeps the existing image unless a replacement is supplied.
- Saved changes appear on the next storefront page request; no duplicate catalogue or WebSocket system.
- User sessions use Laravel authentication; registration and public role assignment are not exposed.
- Sample photos are licensed demo assets. User-uploaded SVGs are rejected.
- The seeder adds missing sample products without resetting existing prices or stock. It fills missing sample photos only when no image exists.
- Categories are seeded; category CRUD, checkout, payments and order management are outside the assessment scope.

## Project map

- `routes/web.php`: public, login and protected admin routes
- `app/Http/Controllers/StorefrontController.php`: public catalogue and details
- `app/Http/Controllers/Admin/ProductController.php`: explicit CRUD and image lifecycle
- `app/Http/Requests/Admin/ProductRequest.php`: shared server-side product validation and authorization
- `app/Services/ProductImageStorage.php`: raster decoding, WebP encoding, generated filenames and cleanup
- `app/Http/Middleware/EnsureUserIsAdmin.php`: admin authorization
- `app/Console/Commands/CreateAdmin.php`: reproducible admin setup
- `resources/views/layouts`: shared storefront/admin layouts
- `resources/views/admin/products`: product list and shared create/edit form
- `public/css/app.css`: readable custom CSS, grid/flex layouts and responsive breakpoints
- `public/js/app.js`: delete confirmation and image preview; no business rules depend on JavaScript
- `tests/Feature`: HTTP, CRUD, upload, authentication and access tests

## Verification

```sh
php artisan test --compact
vendor/bin/pint --dirty --format agent
composer audit
```

Tests use in-memory SQLite and fake upload storage, leaving your MySQL data unchanged. The running application uses MySQL. See `SECURITY.md` and `ERROR_LOG.md` for security details and troubleshooting history.

Screenshots are in `screenshots/`. Desktop storefront, dashboard and product-management views were inspected in the browser. CSS supports narrow screens; mobile viewport emulation did not apply in the available in-app browser, so a real device or browser responsive-mode review remains a final visual check.

## Demo sequence

1. Browse the homepage and its eight products.
2. Search for `drill`, try an unmatched term, then clear the search.
3. Filter a category and open a product detail page.
4. Visit `/admin` signed out and observe the login redirect.
5. Sign in and explain the four dashboard counts.
6. Add a product with a raster image. Verify it appears in the shop.
7. Edit its price, stock, category and image. Hide it and verify its public page returns 404.
8. Demonstrate invalid input and upload rejection.
9. Delete the demonstration product, then log out and retry an admin URL.

## Deployment notes

Serve only Laravel's `public` directory. Use HTTPS, `APP_DEBUG=false`, secure session cookies, protected credentials and non-executable upload storage. Back up MySQL and uploaded files together. This assessment has not been deployed publicly. Source is in the private repository https://github.com/AndaniMagodi/e-commerce-market . Reviewers need repository invitations before they can open the source link. The submission email remains a separate step.


## Assessment requirement check - 14 October 2026

| Requirement | Status and evidence |
| --- | --- |
| Logo, search and navigation | Implemented in the shared storefront header |
| Promotional banner | Implemented on the homepage with a local photograph |
| Categories and at least eight database products | Four seeded categories and eight products; data queried through Eloquent |
| Product image, name, price and availability | Displayed in cards, with local licensed photos |
| Individual product details | Dedicated route/view; hidden and missing products return 404 |
| Functional search | Name/description search; search and category filtering tested |
| Responsive/mobile-friendly design | CSS breakpoints implemented; desktop inspected; final phone-size visual review still pending |
| Admin email/password login and logout | Implemented and tested; sessions regenerated/invalidated |
| Admin-only access | All admin routes use auth and admin middleware; non-admin rejection tested |
| Four dashboard statistics | Product, active, zero-stock and category counts implemented |
| Product add/edit/delete | Implemented and tested |
| Images, price, category, quantity and availability updates | Implemented; upload replacement and stock/publication validation tested |
| Admin changes reflected on storefront | Same database queried on every new page request; created products verified through public detail requests |
| MySQL and migrations | Running application uses MySQL; users/products/categories and foreign key migrations included |
| SQL injection/XSS/CSRF defenses | Bound queries, escaped Blade output and web CSRF middleware; escaped output tested and live missing-token POST returns 419 |
| Hashing, validation, safe uploads, route authorization and credential handling | Implemented and explained in SECURITY.md; tests cover key boundaries; local secrets excluded from Git |
| GitHub source link | Private repository created; reviewer access still needs invitations |
| README, screenshots, working app and security explanation | Included; screenshots under screenshots/; app running locally |
| Final technical demonstration | Demo sequence and the demo sequence above prepared; candidate rehearsal remains |
| Submission by 16 October 2026, 10:00 | Not sent; applicant must review and submit before deadline |
