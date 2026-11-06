
<?php

use App\Http\Controllers\InventoryAdjustmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Product Inventory Routes
    Route::resource('products', ProductController::class);

    // Inventory Transaction Routes
    Route::resource('transactions', InventoryTransactionController::class);

    // Stock Adjustment Routes
    Route::get('/adjustments/create', [InventoryAdjustmentController::class, 'create'])
        ->name('adjustments.create');

    Route::get('/adjustments/product/{product}', [InventoryAdjustmentController::class, 'product'])
        ->name('adjustments.product');

    Route::post('/adjustments', [InventoryAdjustmentController::class, 'store'])
        ->name('adjustments.store');

    // Reports Route
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    // Notification Routes
    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return back();
    })->name('notifications.read');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';
