<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    
    // Leads Resource Routes
    Route::resource('leads', LeadController::class);
    
    // Additional Lead Routes
    Route::post('/leads/{lead}/mark-contacted', [LeadController::class, 'markContacted'])
        ->name('leads.markContacted');
    
    Route::get('/leads/status/{status}', [LeadController::class, 'byStatus'])
        ->name('leads.byStatus');
    
    Route::post('/leads/{lead}/assign', [LeadController::class, 'assign'])
        ->name('leads.assign');
});

// Redirect /home to /dashboard
Route::get('/home', function () {
    return redirect('/dashboard');
})->middleware('auth')->name('home');