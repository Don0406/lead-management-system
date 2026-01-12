<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationSettingsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ReportController;

// Public routes (if any)
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // ===== NOTIFICATIONS =====
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::get('/stats', [NotificationController::class, 'getStats']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
        Route::delete('/', [NotificationController::class, 'destroyAll']);
    });

    // Notification Settings
    Route::prefix('notification-settings')->group(function () {
        Route::get('/', [NotificationSettingsController::class, 'index']);
        Route::put('/', [NotificationSettingsController::class, 'update']);
        Route::put('/{type}', [NotificationSettingsController::class, 'updateSingle']);
    });

    // ===== ANALYTICS =====
    Route::prefix('analytics')->group(function () {
        Route::get('/dashboard', [AnalyticsController::class, 'dashboard']);
        Route::get('/conversion-rate', [AnalyticsController::class, 'conversionRate']);
    });

    // ===== REPORTS =====
    Route::prefix('reports')->group(function () {
        Route::post('/generate', [ReportController::class, 'generate']);
        Route::get('/daily', [ReportController::class, 'dailyReport']);
        Route::get('/weekly', [ReportController::class, 'weeklyReport']);
        Route::get('/monthly', [ReportController::class, 'monthlyReport']);
    });
    
    // ===== LEADS (Add your existing lead routes here) =====
    // Route::apiResource('leads', LeadController::class);
    // Route::put('/leads/{id}/assign', [LeadController::class, 'assign']);
});