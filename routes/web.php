<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// Admin Dashboard (no auth for testing — add middleware later)
Route::get('/admin/dashboard', [AdminController::class, 'index']);

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Leads Resource Routes
    Route::resource('leads', LeadController::class);

    // Additional Lead Routes
    Route::post('/leads/{lead}/mark-contacted', [LeadController::class, 'markContacted'])
        ->name('leads.markContacted');

    Route::get('/leads/status/{status}', [LeadController::class, 'byStatus'])
        ->name('leads.byStatus');
});

// Redirect /home to /dashboard
Route::get('/home', function () {
    return redirect('/dashboard');
})->middleware('auth')->name('home');


