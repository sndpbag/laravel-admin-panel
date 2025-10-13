# sndpbag Laravel Admin Panel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sndpbag/admin-panel.svg?style=flat-square)](https://packagist.org/packages/sndpbag/admin-panel)
[![Total Downloads](https://img.shields.io/packagist/dt/sndpbag/admin-panel.svg?style=flat-square)](https://packagist.org/packages/sndpbag/admin-panel)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](https://opensource.org/licenses/MIT)

A feature-rich, ready-to-use admin panel for Laravel applications, designed to be both powerful and easy to customize. This package provides a complete backend solution with a beautiful UI, secure authentication, user management, and much more, right out of the box.


## Features

This admin panel is packed with features to help you build your application faster:

-   **Secure Authentication:** Complete auth scaffolding including registration, login, password reset, and email verification.
-   **Two-Factor Authentication (2FA):** OTP-based login for enhanced security.
-   **Full User Management:**
    -   CRUD operations for users.
    -   Soft Deletes with a trash view to restore or permanently delete users.
    -   Advanced search and filtering by status or role.
    -   Quickly toggle user status (Active/Inactive) and role (Admin/User) with a single click.
-   **Data Management:**
    -   **Export:** Export user data to PDF, XLSX, or CSV formats, respecting applied filters.
    -   **Import:** Bulk create users by importing data from an Excel/CSV file with validation.
-   **User Activity Logging:** Automatically logs detailed user login activity, including IP address, location (city/country), and device (browser, platform).
-   **Customizable Dashboard:**
    -   A beautiful, modern dashboard layout.
    -   Theme customization settings to change colors and fonts.
    -   Config-driven sidebar menu, allowing you to add new navigation items without touching the package code.
-   **Profile Management:** Users can update their profile information, change their password, and manage notification settings.
-   **Modern UI/UX:** Built with Tailwind CSS, featuring a responsive design, skeleton loaders for a better UX, and a dark mode toggle.

## Installation

You can install the package via Composer.

1.  **Require the package:**
    ```bash
    composer require sndpbag/admin-panel
    ```
    
      ```bash
    composer require sndpbag/admin-panel:dev-main
    ```



2.  **Publish Assets and Configuration:**
    This command will publish the necessary assets (JS, CSS), configuration files, and migrations to your project.
    ```bash
    php artisan vendor:publish --provider="Sndpbag\AdminPanel\Providers\AdminPanelServiceProvider"
    ```
    *You can also publish assets and configs separately using the tags `admin-panel-assets` and `admin-panel-config`.*

3.  **Run Migrations:**
    This will create the necessary tables in your database, including `users`, `user_logs`, and others.
    ```bash
    php artisan migrate
    ```


    4.  **Run command:**
    Creates a symbolic link between public/storage and storage/app/public
    ```bash
    php artisan storage:link
    ```

5.  **Configure Mail Settings:**
    Since the package includes email verification and OTP notifications, ensure your `.env` file is configured correctly for sending emails.
    ```dotenv
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=your_username
    MAIL_PASSWORD=your_password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"
    ```

## Usage

After installation, you can access the admin panel by visiting the following routes:

-   **Login:** `your-app-url/login`
-   **Register:** `your-app-url/register`
-   **Dashboard:** `your-app-url/dashboard` (requires login)

### Customizing the Sidebar

You can easily add new items to the dashboard sidebar without modifying the package's code.

1.  First, publish the configuration file if you haven't already:
    ```bash
    php artisan vendor:publish --tag="admin-panel-config"
    ```

2.  Now, open `config/admin-panel.php` in your project and add a new entry to the `sidebar` array. For example, to add a "Products" link:
    ```php
    'sidebar' => [
        // ... default menu items
        [
            'title' => 'Products',
            'route' => 'products.index', // Make sure this route exists in your project
            'icon' => '<svg class="w-6 h-6" ...>...</svg>', // Your custom SVG icon
            'active_on' => 'products.*' // The link will be active on routes like products.index, products.create, etc.
        ],
    ]
    ```

### Extending the Layout

You can use the admin panel's beautiful layout for your own pages. In your Blade view, simply extend the package's layout:

```blade
@extends('admin-panel::dashboard.layouts.app')

@section('title', 'My Custom Page')
@section('page-title', 'Page Title Here')

@section('content')
    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl">Hello from my custom page!</h1>
    </div>
@endsection