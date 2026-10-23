<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [HomeController::class, 'services'])->name('services');
Route::get('/lacak-pesanan', [HomeController::class, 'tracking'])->name('tracking');
Route::get('/lacak-pesanan/{code}', [HomeController::class, 'trackingResult'])->name('tracking.result');

// Authentication / Role Redirection Route
Route::get('/dashboard', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.orders.index');
    }
    return redirect()->route('login');
})->middleware(['auth'])->name('dashboard');

// Customer Protected Routes
Route::middleware(['auth', 'customer'])->prefix('pelanggan')->name('customer.')->group(function () {
    // Orders
    Route::get('/pesanan', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/baru', [CustomerOrderController::class, 'create'])->name('orders.create');
    Route::post('/pesanan', [CustomerOrderController::class, 'store'])->name('orders.store');
    Route::get('/pesanan/{id}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::get('/pesanan/{id}/invoice', [CustomerOrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('/pesanan/{id}/batal', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');

    // Payment Proof Upload
    Route::post('/pesanan/{id}/pembayaran', [CustomerPaymentController::class, 'uploadProof'])->name('payments.proof');

    // Reviews
    Route::post('/pesanan/{id}/ulasan', [CustomerReviewController::class, 'store'])->name('reviews.store');
});

// Admin Protected Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Order Management
    Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/pesanan/{id}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    Route::patch('/pesanan/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Services
    Route::get('/layanan', [AdminServiceController::class, 'index'])->name('services.index');
    Route::post('/layanan', [AdminServiceController::class, 'store'])->name('services.store');
    Route::put('/layanan/{id}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::delete('/layanan/{id}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

    // Payments
    Route::get('/pembayaran', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('/pembayaran/{id}/verifikasi', [AdminPaymentController::class, 'verify'])->name('payments.verify');

    // Reports & Exports
    Route::get('/laporan', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/pesanan/export', [AdminReportController::class, 'exportOrders'])->name('reports.orders.export');
    Route::get('/laporan/layanan/export', [AdminReportController::class, 'exportServices'])->name('reports.services.export');
});

// Notifications
Route::middleware('auth')->group(function () {
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifikasi/{notification}/baca', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// Profile Management (Default Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
