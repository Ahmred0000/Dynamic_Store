<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Worker;
use App\Http\Controllers\Customer;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\MovementController;


Route::get('/', function () {
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->roles->first()->name ?? '';
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'worker') return redirect()->route('worker.dashboard');
        if ($role === 'customer') return redirect()->route('customer.dashboard');
    }
    return redirect()->route('login');
});

// ===== Admin Routes =====
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', Admin\ProductController::class);
        Route::resource('users', Admin\UserController::class);
        Route::resource('categories', Admin\CategoryController::class)->except(['create', 'edit', 'show']);
        Route::get('/reports/low-stock', [Admin\ReportController::class, 'lowStock'])->name('reports.low-stock');
        Route::get('/reports/low-stock/export', [Admin\ReportController::class, 'exportLowStock'])->name('reports.export-low-stock');
        // مسارات حركة المخزن والطلبات
        Route::get('/movements', [MovementController::class, 'index'])->name('movements.index');
        Route::post('/movements/order-approve/{id}', [MovementController::class, 'approveOrder'])->name('movements.order-approve');
        Route::post('/movements/order-reject/{id}', [MovementController::class, 'rejectOrder'])->name('movements.order-reject');
        Route::delete('/movements/clear-logs/{workerId}', [MovementController::class, 'clearWorkerLogs'])->name('movements.clear-logs');
        Route::get('/movements/invoice/{orderId}', [MovementController::class, 'printInvoice'])->name('movements.print-invoice');
        Route::get('/movements/worker-report/{workerId}', [MovementController::class, 'printWorkerReport'])->name('movements.worker-report');
});


// ===== Worker Routes =====
Route::prefix('worker')
    ->name('worker.')
    ->middleware(['auth', 'role:worker'])
    ->group(function () {
        Route::get('/dashboard', [Worker\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/inventory', [Worker\InventoryController::class, 'index'])->name('inventory');
        Route::post('/inventory/deduct', [Worker\InventoryController::class, 'deduct'])->name('inventory.deduct');
    });

// ===== Customer Routes =====
Route::prefix('customer')
    ->name('customer.')
    ->middleware(['auth', 'role:customer'])
    ->group(function () {
        Route::get('/dashboard', [Customer\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('orders', Customer\OrderController::class);

        // ✨ التعديل هنا: شيلنا التكرار عشان يقرأ الـ Prefix والـ Name صح زي الـ Resource
        Route::get('orders/{order}/print', [Customer\OrderController::class, 'print'])->name('orders.print');
    });

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
