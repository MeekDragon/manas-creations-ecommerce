# 🛍️ Manas Creations — Laravel Application

<div align="center">

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

<p align="center">
  <b>A Premium Acrylic Products E-Commerce & Inquiry Showcase Portal</b><br>
  Featuring a robust storefront catalog, dynamic WhatsApp-integrated inquiry forms, and a secure administration dashboard.
</p>

</div>

---

## ✨ Features Tour

*   **Public Storefront**: Modern hero showcase, comprehensive product catalog with interactive category filters, "Why Us" corporate grid, and contact form.
*   **WhatsApp Integration**: Instant, customized product inquiry links per product card.
*   **Administrative Dashboard (`/admin`)**:
    *   Protected by session-based authentication shields.
    *   Overview charts displaying live inquiry statistics.
    *   Products CRUD (Create, Read, Update, Delete) with secure multi-format image uploads.
    *   Inquiries Tracker with toggle-read flags and batch deletion.

---

## 🛠️ Step-by-Step Local Setup

Ensure you have PHP 8.2+, Composer, and Node.js/NPM installed. Run the following steps:

### 1. Clone & Install PHP/Node Packages
```bash
git clone https://github.com/MeekDragon/manas-creations-ecommerce.git
cd manas-creations-ecommerce
composer install
npm install
```

### 2. Copy and Configure Environment Secrets
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Set Up the Database in `.env`
Specify your local database parameters:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manas_creations
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

### 4. Seed and Symlink Files
Migrate schemas, seed default product lists, and link the public filesystems for asset loading:
```bash
php artisan migrate --seed
php artisan storage:link
```

### 5. Build Assets & Start servers
Compile frontend CSS/JS files and fire up the Laravel host:
```bash
npm run dev
php artisan serve
```
Visit `http://localhost:8000` to browse the store!  
Visit `http://localhost:8000/admin` to access the administrator panel.
---

## 🏛️ Project Directory Blueprint

```
app/
  Http/
    Controllers/
      PublicController.php   — Renders homepage
      AdminController.php    — Handles session security
      ProductController.php  — Product management & uploads
      InquiryController.php  — Inquiry pipelines
    Middleware/
      AdminAuth.php           — Guarding admin gates
  Models/
    Product.php
    Inquiry.php

resources/views/
  layouts/
    app.blade.php            — Main storefront shell
    admin.blade.php          — Dashboard layout
  public/
    home.blade.php           — Public home
  admin/
    login.blade.php
    dashboard.blade.php
    inquiries.blade.php
    products.blade.php
```

---

## 📄 License

This project is licensed under the terms of the [MIT License](LICENSE).
