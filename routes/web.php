<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\HouseController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboard;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Houses & Rooms
    Route::resource('houses', HouseController::class);
    Route::post('houses/{house}/rooms', [HouseController::class, 'storeRoom'])->name('houses.rooms.store');
    Route::delete('houses/rooms/{room}', [HouseController::class, 'destroyRoom'])->name('houses.rooms.destroy');

    // Tenants
    Route::resource('tenants', TenantController::class);
    Route::get('tenants/{tenant}/advance-payments', [TenantController::class, 'advancePayments'] )->name('tenants.advance-payments');
    Route::post('tenants/{tenant}/store-advance-payments', [TenantController::class, 'storeAdvancePayment'] )->name('tenants.store-advance-payment');
    // Payments & Settlement
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::get('payment/latest-unit/{id}', [PaymentController::class, 'latestUnit'])->name('payments.latest-unit');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    
    Route::get('payments/settlement/{tenant}', [PaymentController::class, 'settlement'])->name('payments.settlement');
    Route::post('payments/settlement/{tenant}', [PaymentController::class, 'processSettlement'])->name('payments.process-settlement');

    // Reports
    Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/invoice/{rent}', [App\Http\Controllers\Admin\ReportController::class, 'invoice'])->name('reports.invoice');
    Route::post('rent/update-status/{id}', [App\Http\Controllers\Admin\ReportController::class, 'updateStatus'])->name('rent.update-status');

});

// Tenant Routes
Route::middleware(['auth', 'role:tenant'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/dashboard', [TenantDashboard::class, 'index'])->name('dashboard');
    Route::get('/histroy', [TenantDashboard::class, 'histroy'])->name('histroy');
});
