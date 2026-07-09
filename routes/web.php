<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetCodeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// ---------- Guest routes ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetCodeController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetCodeController::class, 'store'])->name('password.email');

    Route::get('/forgot-password/verify', [PasswordResetCodeController::class, 'showVerifyForm'])->name('password.code.verify.show');
    Route::post('/forgot-password/verify', [PasswordResetCodeController::class, 'verifyCode'])->name('password.code.verify');

    Route::get('/reset-password/{token}', [PasswordResetCodeController::class, 'showResetForm'])->name('password.reset.code');
    Route::post('/reset-password', [PasswordResetCodeController::class, 'resetPassword'])->name('password.reset.code.submit');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Load debug routes (development only)
if (file_exists(__DIR__.'/debug.php')) {
    require __DIR__.'/debug.php';
}

// ---------- Authenticated routes ----------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read_all');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::middleware('role:admin,staff')->group(function () {
            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
            Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
        });
        // categories index visible to admin & staff
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        // category detail (products list) for admin and manager
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show')->middleware('role:admin,manager');

        Route::get('/borrowings/{borrowing}/return', [BorrowingController::class, 'showReturnForm'])->name('borrowings.return.form')->middleware('role:admin,staff');
        Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return')->middleware('role:admin,staff');

    // Only admin can create/update/delete categories and users
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::delete('/borrowings/{borrowing}', [BorrowingController::class, 'destroy'])->name('borrowings.destroy');

        Route::get('/reports/users/export/excel', [ReportController::class, 'exportUsersExcel'])->name('reports.users.export.excel');
        Route::get('/reports/users/export/pdf', [ReportController::class, 'exportUsersPdf'])->name('reports.users.export.pdf');
    });

    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/reports/categories/export/excel', [ReportController::class, 'exportCategoriesExcel'])->name('reports.categories.export.excel');
        Route::get('/reports/categories/export/pdf', [ReportController::class, 'exportCategoriesPdf'])->name('reports.categories.export.pdf');
        Route::get('/reports/products/export/excel', [ReportController::class, 'exportProductsExcel'])->name('reports.products.export.excel');
        Route::get('/reports/products/export/pdf', [ReportController::class, 'exportProductsPdf'])->name('reports.products.export.pdf');
    });

    // Admin & Staff: melihat data inventaris
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('role:admin,staff,manager');
    // Only admin can delete products
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('role:admin');
    Route::post('/products/{product}/approve', [ProductController::class, 'approve'])->name('products.approve')->middleware('role:admin');
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index')->middleware('role:admin,staff,manager');
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show')->middleware('role:admin,staff,manager');

    // Profile account management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Staff and manager: riwayat peminjaman yang diproses oleh akun staff
    Route::middleware('role:staff,manager')->group(function () {
        Route::get('/staff/riwayat', [BorrowingController::class, 'staffHistory'])->name('staff.riwayat');
    });

    // Reports (Admin & Manager)
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/reports/borrowings/export/excel', [\App\Http\Controllers\ReportController::class, 'exportBorrowingsExcel'])->name('reports.borrowings.export.excel');
        Route::get('/reports/borrowings/export/pdf', [\App\Http\Controllers\ReportController::class, 'exportBorrowingsPdf'])->name('reports.borrowings.export.pdf');
        Route::get('/reports/borrowings/export/xlsx', [\App\Http\Controllers\ReportController::class, 'exportBorrowingsXlsx'])->name('reports.borrowings.export.xlsx');
    });

    // Manager pages
    Route::middleware('role:manager')->prefix('manager')->group(function () {
        Route::get('/', [\App\Http\Controllers\ManagerController::class, 'index'])->name('manager.index');
        Route::get('/available', [\App\Http\Controllers\ManagerController::class, 'available'])->name('manager.available');
        Route::get('/borrowed', [\App\Http\Controllers\ManagerController::class, 'borrowed'])->name('manager.borrowed');
        Route::get('/returned', [\App\Http\Controllers\ManagerController::class, 'returned'])->name('manager.returned');
            Route::get('/late', [\App\Http\Controllers\ManagerController::class, 'late'])->name('manager.late');
            Route::get('/borrowings', [\App\Http\Controllers\ManagerController::class, 'borrowings'])->name('manager.borrowings');
        Route::get('/active-borrowers', [\App\Http\Controllers\ManagerController::class, 'activeBorrowers'])->name('manager.activeBorrowers');
    });
});
