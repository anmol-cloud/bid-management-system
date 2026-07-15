<?php

use App\Http\Controllers\Admin\AssignmentController as AdminAssignmentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectManager\BidController;
use App\Http\Controllers\SalesManager\AssignmentController as SmAssignmentController;
use App\Http\Controllers\SalesManager\ProjectManagerController;
use App\Http\Controllers\SalesManager\UpworkAccountController;
use App\Http\Controllers\Shared\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// ---- Guest routes ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

// ---- Authenticated routes ----
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications - all roles
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    // ---- Admin only ----
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/assignments', [AdminAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/data', [AdminAssignmentController::class, 'data'])->name('assignments.data');
        Route::post('/assignments', [AdminAssignmentController::class, 'store'])->name('assignments.store');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/analytics', [ReportController::class, 'analytics'])->name('reports.analytics');
        Route::get('/reports/{report}/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
        Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
    });

    // ---- Admin + Sales Manager ----
    Route::middleware('role:admin,sales_manager')->prefix('sales-manager')->name('sales-manager.')->group(function () {
        Route::get('/project-managers', [ProjectManagerController::class, 'index'])->name('pms.index');
        Route::get('/project-managers/data', [ProjectManagerController::class, 'data'])->name('pms.data');
        Route::post('/project-managers', [ProjectManagerController::class, 'store'])->name('pms.store');
        Route::put('/project-managers/{projectManager}', [ProjectManagerController::class, 'update'])->name('pms.update');
        Route::delete('/project-managers/{projectManager}', [ProjectManagerController::class, 'destroy'])->name('pms.destroy');

        Route::get('/upwork-accounts', [UpworkAccountController::class, 'index'])->name('upwork.index');
        Route::get('/upwork-accounts/data', [UpworkAccountController::class, 'data'])->name('upwork.data');
        Route::post('/upwork-accounts', [UpworkAccountController::class, 'store'])->name('upwork.store');
        Route::put('/upwork-accounts/{upworkAccount}', [UpworkAccountController::class, 'update'])->name('upwork.update');
        Route::delete('/upwork-accounts/{upworkAccount}', [UpworkAccountController::class, 'destroy'])->name('upwork.destroy');

        Route::post('/assign-pm', [SmAssignmentController::class, 'assignToPm'])->name('assign-pm');
    });

    // ---- Project Manager (+ admin can peek) ----
    Route::middleware('role:admin,sales_manager,project_manager')->prefix('project-manager')->name('project-manager.')->group(function () {
        Route::get('/bids', [BidController::class, 'index'])->name('bids.index');
        Route::get('/bids/data', [BidController::class, 'data'])->name('bids.data');
        Route::post('/bids', [BidController::class, 'store'])->name('bids.store');
        Route::put('/bids/{bid}', [BidController::class, 'update'])->name('bids.update');
        Route::delete('/bids/{bid}', [BidController::class, 'destroy'])->name('bids.destroy');
    });
});
