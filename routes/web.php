<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RegisterVendorController;
use App\Http\Controllers\OperationalStaffController;
use App\Http\Controllers\OperationalStaffAssignmentController;
use App\Http\Controllers\OwnerAssignmentController;
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

    // Operational Staff Management Routes - Owner only
    Route::get('/operational-staff', [OperationalStaffController::class, 'index'])->name('operational-staff.index');
    Route::post('/operational-staff', [OperationalStaffController::class, 'store'])->name('operational-staff.store');
    Route::patch('/operational-staff/{operationalStaff}', [OperationalStaffController::class, 'update'])->name('operational-staff.update');
    Route::delete('/operational-staff/{operationalStaff}', [OperationalStaffController::class, 'destroy'])->name('operational-staff.destroy');

    // Owner: review staff assignment requests
    Route::get('/owner-assignments', [OwnerAssignmentController::class, 'index'])->name('owner-assignments.index');
    Route::get('/owner-assignments/history', [OwnerAssignmentController::class, 'history'])->name('owner-assignments.history');
    Route::post('/owner-assignments/{assignment}/approve', [OwnerAssignmentController::class, 'approve'])->name('owner-assignments.approve');
    Route::post('/owner-assignments/{assignment}/decline', [OwnerAssignmentController::class, 'decline'])->name('owner-assignments.decline');
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

// Order Routes - View only (owner, accounting, staff, manager)
Route::middleware(['auth', 'role:owner|staff-accounting|staff|manager', 'check.user.status'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

// Order Routes - Create/Edit/Delete (staff and manager only, NOT accounting)
Route::middleware(['auth', 'role:staff|manager', 'check.user.status'])->group(function () {
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

// Staff Assignment Routes - Accounting only
Route::middleware(['auth', 'role:staff-accounting', 'check.user.status'])->group(function () {
    Route::get('/staff-assignments', [OperationalStaffAssignmentController::class, 'index'])->name('staff-assignments.index');
    Route::get('/staff-assignments/create', [OperationalStaffAssignmentController::class, 'create'])->name('staff-assignments.create');
    Route::post('/staff-assignments', [OperationalStaffAssignmentController::class, 'store'])->name('staff-assignments.store');
    Route::delete('/staff-assignments/{staffAssignment}', [OperationalStaffAssignmentController::class, 'destroy'])->name('staff-assignments.destroy');
    
    // Order detail report
    Route::get('/staff-assignments/order/{orderType}/{orderId}', [OperationalStaffAssignmentController::class, 'showOrderDetail'])->name('staff-assignments.order-detail');
    
    // Expense management for assignments
    Route::get('/staff-assignments/{assignment}/expenses', [\App\Http\Controllers\OperationalExpenseController::class, 'index'])->name('staff-assignments.expenses.index');
    Route::post('/staff-assignments/{assignment}/expenses', [\App\Http\Controllers\OperationalExpenseController::class, 'store'])->name('staff-assignments.expenses.store');
    Route::delete('/staff-assignments/{assignment}/expenses/{expense}', [\App\Http\Controllers\OperationalExpenseController::class, 'destroy'])->name('staff-assignments.expenses.destroy');
});

// Invoice Routes - Owner and Accounting
Route::middleware(['auth', 'role:owner|staff-accounting', 'check.user.status'])->group(function () {
    Route::get('/invoices', [\App\Http\Controllers\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [\App\Http\Controllers\InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [\App\Http\Controllers\InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', [\App\Http\Controllers\InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::patch('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
    Route::get('/invoices/{invoice}/revision', [\App\Http\Controllers\InvoiceController::class, 'createRevision'])->name('invoices.revision');
    Route::post('/invoices/{invoice}/revision', [\App\Http\Controllers\InvoiceController::class, 'storeRevision'])->name('invoices.revision.store');
});

require __DIR__.'/auth.php';

