# Setup Internal Website - Laravel Project

## ✅ Yang Sudah Terinstall

### 1. Laravel Framework
- Laravel 10.x (compatible dengan PHP 8.1)
- Project name: **internal-website**
- Location: `C:\Freelance\Project 1\Code\internal-website`

### 2. Laravel Breeze (Authentication)
- Login/Logout system
- Register user
- Password reset
- Email verification
- Profile management
- Blade template (bisa di-customize sesuai design)

### 3. Spatie Laravel Permission (Role & Permission Management)
- Role-based access control
- Permission management
- Middleware protection
- Database tables untuk roles & permissions

## 📋 Langkah Selanjutnya

### 1. Buat Database MySQL
Buka **phpMyAdmin** atau MySQL client, lalu jalankan file:
```
database/create_database.sql
```

Atau manual buat database dengan nama: `internal_website`

### 2. Konfigurasi Database
File `.env` sudah dikonfigurasi:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=internal_website
DB_USERNAME=root
DB_PASSWORD=
```

**Sesuaikan** `DB_PASSWORD` jika MySQL kamu pakai password.

### 3. Jalankan Migration
Setelah database dibuat, jalankan:
```bash
php artisan migrate
```

Ini akan membuat tables:
- users
- password_reset_tokens
- sessions
- roles
- permissions
- model_has_roles
- model_has_permissions
- role_has_permissions

### 4. Start Development Server
```bash
php artisan serve
```

Buka browser: `http://localhost:8000`

## 🔐 Authentication Routes

Breeze sudah generate routes berikut:
- `/register` - Register user baru
- `/login` - Login page
- `/dashboard` - Dashboard (setelah login)
- `/profile` - Edit profile
- `/logout` - Logout

## 👥 Setup Role & Permission

### Contoh Setup Role
```php
// Di tinker atau seeder
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Buat roles
Role::create(['name' => 'owner']);
Role::create(['name' => 'staff-accounting']);
Role::create(['name' => 'staff']);

// Buat permissions
Permission::create(['name' => 'view-dashboard']);
Permission::create(['name' => 'manage-accounting']);
Permission::create(['name' => 'manage-users']);

// Assign permission ke role
$owner = Role::findByName('owner');
$owner->givePermissionTo(['view-dashboard', 'manage-accounting', 'manage-users']);

$accounting = Role::findByName('staff-accounting');
$accounting->givePermissionTo(['view-dashboard', 'manage-accounting']);

$staff = Role::findByName('staff');
$staff->givePermissionTo('view-dashboard');
```

### Assign Role ke User
```php
$user = User::find(1);
$user->assignRole('owner');
```

### Protect Routes dengan Role
```php
// routes/web.php
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
});

Route::middleware(['auth', 'role:staff-accounting'])->group(function () {
    Route::get('/accounting', [AccountingController::class, 'index']);
});
```

### Check Role di Blade
```blade
@role('owner')
    <p>Ini hanya tampil untuk Owner</p>
@endrole

@can('manage-accounting')
    <a href="/accounting">Kelola Accounting</a>
@endcan
```

## 🎨 Customize Design

File views ada di:
```
resources/views/
├── auth/
│   ├── login.blade.php          ← Login page
│   ├── register.blade.php       ← Register page
│   ├── forgot-password.blade.php
│   └── ...
├── layouts/
│   ├── app.blade.php            ← Main layout
│   ├── guest.blade.php          ← Layout untuk guest (belum login)
│   └── navigation.blade.php     ← Navbar
└── dashboard.blade.php          ← Dashboard page
```

CSS/JS ada di:
```
resources/
├── css/
│   └── app.css                  ← Edit CSS di sini
└── js/
    └── app.js                   ← Edit JS di sini
```

Setelah edit CSS/JS, compile dengan:
```bash
npm run dev        # Development mode (watch changes)
npm run build      # Production build
```

## 📦 Dependencies

### PHP Packages (Composer)
- laravel/framework ^10.0
- laravel/breeze ^1.29
- spatie/laravel-permission ^6.24

### Node Packages (NPM)
- vite
- tailwindcss
- @tailwindcss/forms
- alpinejs

## 🗄️ Database Structure

Setelah migration, struktur database:
- `users` - Data user (email, password, name)
- `roles` - List role (owner, staff, dll)
- `permissions` - List permission
- `model_has_roles` - Relasi user-role
- `model_has_permissions` - Relasi user-permission
- `role_has_permissions` - Relasi role-permission

## 🚀 Tips Development

1. **Generate dummy users untuk testing:**
   ```bash
   php artisan tinker
   ```
   ```php
   User::factory()->create(['email' => 'owner@test.com', 'password' => bcrypt('password')])->assignRole('owner');
   ```

2. **Clear cache jika ada masalah:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Optimize untuk production:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

## 📚 Dokumentasi

- Laravel: https://laravel.com/docs/10.x
- Laravel Breeze: https://laravel.com/docs/10.x/starter-kits#laravel-breeze
- Spatie Permission: https://spatie.be/docs/laravel-permission
