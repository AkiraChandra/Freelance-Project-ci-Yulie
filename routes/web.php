<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RegisterVendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'check.user.status'])->name('dashboard');

Route::middleware(['auth', 'check.user.status'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User Management Routes - Only for Owner role
Route::middleware(['auth', 'role:owner', 'check.user.status'])->group(function () {
    Route::get('/users/manage', [UserManagementController::class, 'index'])->name('users.manage');
    Route::post('/users/{id}/approve', [UserManagementController::class, 'approve'])->name('users.approve');
    Route::post('/users/{id}/reject', [UserManagementController::class, 'reject'])->name('users.reject');
    Route::post('/users/{id}/assign-role', [UserManagementController::class, 'assignRole'])->name('users.assign-role');
    Route::post('/users/{id}/remove-role', [UserManagementController::class, 'removeRole'])->name('users.remove-role');

    // Vendor Management Routes
    Route::get('/vendors/register', [RegisterVendorController::class, 'index'])->name('vendor.register');
    Route::post('/vendors', [RegisterVendorController::class, 'store'])->name('vendor.store');
    Route::get('/vendors/{vendor}', [RegisterVendorController::class, 'show'])->name('vendor.show');
    Route::get('/vendors/{vendor}/edit', [RegisterVendorController::class, 'edit'])->name('vendor.edit');
    Route::patch('/vendors/{vendor}', [RegisterVendorController::class, 'update'])->name('vendor.update');
    Route::delete('/vendors/{vendor}', [RegisterVendorController::class, 'destroy'])->name('vendor.destroy');
    Route::delete('/vendor-prices/{vendorPrice}', [RegisterVendorController::class, 'destroyPrice'])->name('vendor-price.destroy');
});

// Company Management Routes - For Staff roles
Route::middleware(['auth', 'role:staff-accounting|staff|manager', 'check.user.status'])->group(function () {
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::patch('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
});

// Customer Management Routes - For Staff roles
Route::middleware(['auth', 'role:staff-accounting|staff|manager', 'check.user.status'])->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::patch('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
});

// Order Routes - For Staff roles
Route::middleware(['auth', 'role:staff-accounting|staff|manager', 'check.user.status'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'selectType'])->name('orders.select-type');

    // Import
    Route::get('/orders/import/create', [OrderController::class, 'createImport'])->name('import-orders.create');
    Route::post('/orders/import', [OrderController::class, 'storeImport'])->name('import-orders.store');
    Route::get('/orders/import/{importOrder}/edit', [OrderController::class, 'editImport'])->name('import-orders.edit');
    Route::patch('/orders/import/{importOrder}', [OrderController::class, 'updateImport'])->name('import-orders.update');
    Route::delete('/orders/import/{importOrder}', [OrderController::class, 'destroyImport'])->name('import-orders.destroy');

    // Export
    Route::get('/orders/export/create', [OrderController::class, 'createExport'])->name('export-orders.create');
    Route::post('/orders/export', [OrderController::class, 'storeExport'])->name('export-orders.store');
    Route::get('/orders/export/{exportOrder}/edit', [OrderController::class, 'editExport'])->name('export-orders.edit');
    Route::patch('/orders/export/{exportOrder}', [OrderController::class, 'updateExport'])->name('export-orders.update');
    Route::delete('/orders/export/{exportOrder}', [OrderController::class, 'destroyExport'])->name('export-orders.destroy');
});

require __DIR__.'/auth.php';

