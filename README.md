# sndpbag Laravel Admin Panel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sndpbag/admin-panel.svg?style=flat-square)](https://packagist.org/packages/sndpbag/admin-panel)
[![Total Downloads](https://img.shields.io/packagist/dt/sndpbag/admin-panel.svg?style=flat-square)](https://packagist.org/packages/sndpbag/admin-panel)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](https://opensource.org/licenses/MIT)

A feature-rich, ready-to-use admin panel for Laravel applications, designed to be both powerful and easy to customize. This package provides a complete backend solution with a beautiful UI, secure authentication, user management, PWA support, and much more, right out of the box.

## Requirements
- PHP 8.1+
- Laravel 10.0+
- GD Extension (Required for Captcha functionality)

## 🚀 Features

This admin panel is packed with features to help you build your application faster:

### 🔐 Security & Authentication
-   **Secure Authentication:** Complete auth scaffolding including registration, login, password reset, and email verification.
-   **Two-Factor Authentication (OTP):** Secure login with OTP-based 2FA.
-   **Captcha Protection:** Built-in captcha for login and registration forms.

### 👤 User Management
-   **CRUD Operations:** Create, Read, Update, and Delete users easily.
-   **Role Management:** Assign Admin or User roles with a single click.
-   **Soft Deletes:** Trash system to restore or permanently delete users.
-   **Advanced Filtering:** Filter users by status, role, or search keywords.
-   **Activity Logging:** Tracks user login activity (IP, Location, Device).

### 🎨 Theme & Customization (New!)
-   **Dynamic Themes:** Customize Primary, Secondary, and Accent colors directly from the dashboard.
-   **Dark Mode 3.0:** Toggle between **Light**, **Dark**, and **System (Auto)** modes.
-   **Persistent Settings:** Theme preferences are saved to the database and sync across devices.
-   **Font Customization:** Choose from multiple font families (Poppins, Inter, Roboto).

### 📱 PWA (Progressive Web App) Support (New!)
-   **Installable:** Users can install the dashboard as an app on Desktop and Mobile.
-   **Offline Mode:** Works even when the internet is down (displays cached pages/offline fallback).
-   **Fast Loading:** Service Worker caches static assets for instant load times.

### 🛠️ Developer Friendly
-   **Config-Driven Sidebar:** Add menu items via `config/admin-panel.php` without touching core code.
-   **View Customization:** Publish and modify blade views to match your design.
-   **Data Export:** Export user lists to PDF, CSV, or Excel.

---

## 📦 Installation

You can install the package via Composer.

1.  **Require the package:**
    ```bash
    composer require sndpbag/admin-panel
    ```

2.  **Publish Assets and Configuration:**
    This command will publish the necessary assets (JS, CSS), configuration files, and migrations.
    ```bash
    php artisan vendor:publish --provider="Sndpbag\AdminPanel\Providers\AdminPanelServiceProvider"
    ```

3.  **Run Migrations:**
    Create the necessary tables in your database.
    ```bash
    php artisan migrate
    ```

4.  **Setup RBAC (Roles & Permissions):**
    Sync application routes to permissions and create default roles.
    ```bash
    # Sync routes to permissions
    php artisan dynamic-roles:sync-routes

    # Seed default roles (Admin, Editor, User) and assign permissions
    php artisan db:seed --class=Sndpbag\AdminPanel\Database\Seeders\RolesAndPermissionsSeeder
    ```

5.  **Link Storage:**
    Link the storage folder to public for profile images and uploads.
    ```bash
    php artisan storage:link
    ```

5.  **Configure Mail Settings:**
    Ensure your `.env` file is configured for verified emails and OTPs.
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

---

## 🔑 Roles & Permissions

This package comes with a built-in Role-Based Access Control (RBAC) system.

### Create a Super Admin
You can generate a Super Admin user with full access to the system using the following command:

```bash
php artisan admin-panel:make-super-admin
```

-   The command will ask if you want to create a **New User** or assign the role to an **Existing User**.
-   The default login credentials for a new super admin (if you don't customized) are typically:
    -   **Role:** Super Admin

### Assign Roles or Permissions
To manually assign roles or direct permissions to a user via the command line:

```bash
php artisan admin-panel:assign-access
```

1.  Enter the user's **Email Address**.
2.  Choose **Role** or **Permission**.
3.  Select the desired Role/Permission from the list.

### Syncing Permissions
If you add new routes or want to refresh the permission list based on your route names:

```bash
php artisan dynamic-roles:sync-routes
```

---

## 🌐 Social Login (Google & Facebook)

Enable users to log in with their Google or Facebook accounts.

### 1. Install Socialite
First, install the Laravel Socialite package in your main application:

```bash
composer require laravel/socialite
```

### 2. Configure Credentials
Add your social app credentials to your `.env` file and `config/services.php`.

#### Environment (.env)
```env
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URL=http://your-domain.com/login/google/callback

FACEBOOK_CLIENT_ID=your-facebook-client-id
FACEBOOK_CLIENT_SECRET=your-facebook-client-secret
FACEBOOK_REDIRECT_URL=http://your-domain.com/login/facebook/callback
```
> **Note:** If testing locally with XAMPP/WAMP, your redirect URL might look like:
> `http://localhost/your-project-folder/public/login/google/callback`

#### Services Config (`config/services.php`)
Ensure these keys exist in your application's `config/services.php` file:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URL'),
],

'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URL'),
],
```

### 3. Enable the Buttons
Open `config/admin-panel.php` (published to your config folder) and set the providers to `true`:

```php
'social_login' => [
    'google' => true,
    'facebook' => false,
],
```

---

## 📖 Usage

Access the admin panel at:

-   **Login:** `/login`
-   **Register:** `/register`
-   **Dashboard:** `/dashboard`

### Customizing Views
If you need to modify the design or logic of the dashboard views, you can publish them to your resources folder:

```bash
php artisan vendor:publish --tag=admin-panel-views
```
Files will be copied to `resources/views/vendor/admin-panel`. Any changes here will override the package defaults.

### PWA Setup (Manual Step)
The package includes PWA assets, but you need to add your own icons:
1.  Navigate to `public/images/icons/`.
2.  Add your app icons (must be named `icon-192x192.png` and `icon-512x512.png` etc).


### Adding Sidebar Items
Open `config/admin-panel.php` and add to the `sidebar` array:

```php
'sidebar' => [
    // ...
    [
        'title' => 'My Page',
        'route' => 'my.route',
        'icon' => '<svg>...</svg>',
        'active_on' => 'my.route*'
    ],
]
```

### Extending the Layout
To create your own pages using the dashboard layout:

```blade
@extends('admin-panel::dashboard.layouts.app')

@section('title', 'My Page')

@section('content')
    <div class="card">
        <h1>Welcome to My Custom Page</h1>
    </div>
@endsection
```

---

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.