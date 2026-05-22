# Manas Creations — Laravel Application

A premium acrylic products e-commerce/showcase site with a full admin panel, converted from a standalone HTML file to a Laravel 11 application.

## Features

- **Public storefront** — Hero section, product catalog with category filtering, Why Us, contact form
- **WhatsApp integration** — Direct enquiry links per product
- **Product enquiry modal** — Customers can send queries from any product card
- **Admin panel** (`/admin`) — Protected by session auth
  - Dashboard with live stats
  - Inquiries management (view, toggle status, delete)
  - Products CRUD (create, edit, delete with image upload)

## Tech Stack

- **Laravel 11** (PHP 8.2+)
- **MySQL / SQLite** — standard Laravel migrations
- **Blade** templates
- **Local file storage** — product images stored in `storage/app/public/products/`

---

## Installation

```bash
# 1. Clone / unzip the project
cd manas-creations

# 2. Install dependencies
composer install

# 3. Copy and configure environment
cp .env.example .env
php artisan key:generate

# 4. Configure your database in .env
# DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 5. Run migrations and seed default products
php artisan migrate --seed

# 6. Create the storage symlink (for image serving)
php artisan storage:link

# 7. Start the dev server
php artisan serve
```

Visit `http://localhost:8000` for the public site.  
Visit `http://localhost:8000/admin` for the admin panel.

**Default credentials:**
- Username: `admin`
- Password: `manas2025`

> ⚠️ Change the admin credentials in `.env` before deploying to production.

---

## Project Structure

```
app/
  Http/
    Controllers/
      PublicController.php   — Home page
      AdminController.php    — Auth + admin pages
      ProductController.php  — Product CRUD + image upload
      InquiryController.php  — Inquiry store, toggle, delete
    Middleware/
      AdminAuth.php           — Session-based admin guard
  Models/
    Product.php
    Inquiry.php

config/
  admin.php                  — Admin credentials config

database/
  migrations/
    ...create_products_table.php
    ...create_inquiries_table.php
  seeders/
    DatabaseSeeder.php       — 6 default products

resources/views/
  layouts/
    app.blade.php            — Public layout
    admin.blade.php          — Admin sidebar layout
  public/
    home.blade.php           — Main storefront page
  admin/
    login.blade.php
    dashboard.blade.php
    inquiries.blade.php
    products.blade.php
    product-form.blade.php   — Create/edit form
  partials/
    product-card.blade.php
    wa-icon.blade.php

routes/
  web.php
```

---

## Deployment Notes

- Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
- Run `php artisan optimize` before going live
- Product images are stored locally. For cloud storage, configure `FILESYSTEM_DISK=s3` and update `ProductController::handleImages()` accordingly.
- The admin uses simple session-based auth (no Laravel Auth). For enhanced security, consider switching to Laravel Breeze.
