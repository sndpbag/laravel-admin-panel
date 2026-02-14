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

## 🔒 Security Features

### Roles Page Security Password

The roles management pages (`/roles`) have an additional security layer. Users with permission to access roles must also enter a security password defined in your `.env` file.

#### Setup

1. Add the security password to your `.env` file:
```env
ROLES_SECURITY_PASSWORD=YourSecurePasswordHere
```

2. Navigate to `/roles` - you'll be prompted for the security password
3. Once verified, the session persists until logout

**Benefits:**
- Extra protection for sensitive role management
- Environment-based password (different for dev/staging/production)
- Session-based verification (no repeated password entry)

---

## 👥 User Activity Logs

Track and monitor user login activities with detailed information.

### Features
- IP Address tracking
- Geographic location (City, Country)
- Device information (OS, Browser)
- Login timestamp
- User-agent details

### Accessing Logs
Navigate to `/user-logs` to view all login activities. Filter by user, date range, or search by location/IP.

### API Integration
User logs are automatically created on successful login. No additional setup required.

---

## 🎛️ Permission Management

### Understanding the Permission System

This package uses a dynamic permission system where permissions are automatically generated from route names.

#### Permission Structure
```
{resource}.{action}
```

**Examples:**
- `users.index` → View users list
- `users.create` → Create new user form
- `users.store` → Save new user
- `users.edit` → Edit user form
- `users.update` → Update user
- `users.destroy` → Delete user

### Creating Permissions for New Routes

#### Method 1: Automatic (Recommended)

1. Create a **named route** in your `routes/web.php`:
```php
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
```

2. Run the sync command:
```bash
php artisan dynamic-roles:sync-routes
```

3. Apply permission middleware to the route:
```php
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index')
    ->middleware('can:posts.index');
```

4. Assign permission to roles via the admin panel at `/roles`

#### Method 2: Manual

Create permissions directly in the database:

```bash
php artisan tinker
```

```php
use Sndpbag\AdminPanel\Models\Permission;

Permission::create([
    'name' => 'Posts Index',
    'slug' => 'posts.index',
    'group_name' => 'posts',
]);
```

### Permission Groups

Permissions are automatically grouped by the first part of the route name:
- `users.*` → Users group
- `roles.*` → Roles group
- `settings.*` → Settings group
- `posts.*` → Posts group

This grouping appears in the roles edit page for better organization.

### Checking Permissions in Code

```php
// Check if user has permission
if ($user->hasPermission('posts.create')) {
    // User can create posts
}

// In Blade views
@can('posts.create')
    <a href="{{ route('posts.create') }}">Create Post</a>
@endcan

// In routes
Route::get('/posts', [PostController::class, 'index'])
    ->middleware('can:posts.index');
```

---

## 🎨 Theme Customization

### Color Themes

Users can customize the dashboard appearance from `/settings`:

**Available Options:**
- **Primary Color** - Main brand color
- **Secondary Color** - Supporting color
- **Accent Color** - Highlight color
- **Font Family** - Choose from Poppins, Inter, Roboto, etc.

### Dark Mode

Three modes available:
- **Light Mode** - Traditional light theme
- **Dark Mode** - Dark theme for reduced eye strain
- **System** - Automatically matches OS preference

Settings are saved per user and persist across sessions.

---

## 📊 Data Export

Export user data in multiple formats:

### Available Formats
- **PDF** - Formatted document
- **CSV** - Compatible with Excel, Google Sheets
- **Excel** - Native `.xlsx` format

### How to Export

1. Navigate to `/users`
2. Click the "Export" dropdown
3. Select your preferred format
4. File downloads automatically

**Export includes:**
- User details (Name, Email, Role, Status)
- Registration date
- Last login information
- Custom filters applied to the list

---

## 🔧 Artisan Commands Reference

### User & Role Management

#### Create Super Admin
```bash
php artisan admin-panel:make-super-admin
```
Creates a new super admin user or assigns super admin role to existing user.

**Options:**
- Interactive mode (default) - Prompts for user details
- Select existing user or create new

#### Assign Access
```bash
php artisan admin-panel:assign-access
```
Assign roles or permissions to users via CLI.

**Steps:**
1. Enter user email
2. Choose Role or Permission
3. Select from available options

### Permission Management

#### Sync Routes to Permissions
```bash
php artisan dynamic-roles:sync-routes
```
Automatically creates permissions for all named routes in your application.

**When to use:**
- After adding new routes
- After deployment
- When permissions are out of sync

#### Seed Default Roles
```bash
php artisan db:seed --class=Sndpbag\\AdminPanel\\Database\\Seeders\\RolesAndPermissionsSeeder
```
Creates default roles (Admin, Editor, User) and assigns permissions.

### Asset Management

#### Publish Views
```bash
php artisan vendor:publish --tag=admin-panel-views
```
Publishes Blade templates to `resources/views/vendor/admin-panel` for customization.

#### Publish Config
```bash
php artisan vendor:publish --provider="Sndpbag\\AdminPanel\\Providers\\AdminPanelServiceProvider"
```
Publishes configuration, assets, and migrations.

#### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```
Clear all cached configurations and compiled views.

---

## 🐛 Troubleshooting

### Common Issues & Solutions

#### 1. 403 Forbidden Error
**Problem:** User gets "Unauthorized" error on a page.

**Solution:**
- Check if user has required permission
- Run `php artisan dynamic-roles:sync-routes`
- Verify permission is assigned to user's role at `/roles`

#### 2. Security Password Not Working
**Problem:** Correct password rejected at `/roles/security-check`.

**Solution:**
- Check `.env` file has `ROLES_SECURITY_PASSWORD=YourPassword`
- Ensure no extra spaces in the password
- Run `php artisan config:clear`
- Clear browser cache and cookies

#### 3. Captcha Not Showing
**Problem:** Login captcha doesn't display.

**Solution:**
- Verify GD extension is installed: `php -m | grep -i gd`
- Check file permissions on `storage/framework/sessions`
- Clear cache: `php artisan cache:clear`

#### 4. Email Verification Not Sending
**Problem:** Users don't receive verification emails.

**Solution:**
- Check `.env` mail configuration
- Test mail settings: `php artisan tinker` then `Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });`
- Check spam folder
- Verify `MAIL_FROM_ADDRESS` is set

#### 5. Dark Mode Not Persisting
**Problem:** Dark mode resets on page refresh.

**Solution:**
- Check database connection
- Verify `user_settings` table exists
- Run migrations: `php artisan migrate`
- Clear browser localStorage and retry

#### 6. PWA Not Installing
**Problem:** "Install App" option not appearing.

**Solution:**
- Ensure app is served over HTTPS (required for PWA)
- Check `manifest.json` is accessible at `/manifest.json`
- Verify icon files exist in `public/images/icons/`
- Use supported browser (Chrome, Edge, Safari)

#### 7. Social Login Errors
**Problem:** Google/Facebook login fails.

**Solution:**
- Verify credentials in `.env` match provider console
- Check redirect URLs are exact (including http/https)
- Ensure Laravel Socialite is installed
- Confirm provider is enabled in `config/admin-panel.php`

#### 8. Permission Sync Issues
**Problem:** New routes not appearing in permissions.

**Solution:**
- Ensure routes have names: `->name('posts.index')`
- Run sync command: `php artisan dynamic-roles:sync-routes`
- Check routes: `php artisan route:list`
- Verify route names don't start with excluded prefixes (ignition, debugbar)

---

## 💡 Best Practices

### Security
1. **Strong Passwords:** Use complex passwords for roles security and admin accounts
2. **HTTPS:** Always use HTTPS in production for PWA and secure authentication
3. **Environment Variables:** Never commit `.env` to version control
4. **Regular Updates:** Keep Laravel and this package updated

### Performance
1. **Cache Config:** Run `php artisan config:cache` in production
2. **Optimize Routes:** Run `php artisan route:cache`
3. **Asset Compilation:** Compile assets for production
4. **Database Indexes:** Add indexes on frequently queried columns

### Permissions
1. **Consistent Naming:** Use `resource.action` pattern for all routes
2. **Regular Sync:** Sync permissions after adding routes
3. **Least Privilege:** Give users minimum required permissions
4. **Permission Groups:** Keep related permissions in same group

### Development Workflow
1. **Create Route** with name → 2. **Sync Permissions** → 3. **Apply Middleware** → 4. **Assign to Roles**

---

## 📚 FAQ

### Q: Can I use this package with an existing Laravel project?
**A:** Yes! The package is designed to integrate seamlessly with existing projects. Just install and publish the assets.

### Q: How do I remove the default routes (login, register)?
**A:** Publish the service provider and modify the route registration, or use route middleware to override default behavior.

### Q: Can users have multiple roles?
**A:** Currently, users can have one primary role. You can extend the system to support multiple roles by modifying the relationships.

### Q: How do I add custom permissions not tied to routes?
**A:** Use the manual method described in the Permission Management section or create them via tinker.

### Q: Is this package compatible with Laravel 11?
**A:** Check the packagist page for the latest compatibility. Update the package with `composer update sndpbag/admin-panel`.

### Q: Can I customize the email templates?
**A:** Yes, publish views with `--tag=admin-panel-views` and modify the email templates in the vendor folder.

### Q: How do I change the default landing page?
**A:** Modify the dashboard route in your `routes/web.php` or publish views and customize the dashboard controller.

### Q: Can I disable certain features (like PWA or 2FA)?
**A:** Yes, modify `config/admin-panel.php` to enable/disable features as needed.

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

---

## 🙏 Credits

- [Sndpbag](https://github.com/sndpbag)
- [All Contributors](../../contributors)

---

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.