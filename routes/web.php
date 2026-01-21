<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DirectoryController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('welcome'); })->name('landing');
Route::get('/capabilities/{slug}', [PublicController::class, 'showService'])->name('services.show');
Route::post('/inquiry', [LeadController::class, 'storePublic'])->name('landing.store');
Route::get('/directory/{slug}', [DirectoryController::class, 'show'])->name('directory.show');

Auth::routes();

/*
|--------------------------------------------------------------------------
| Secure Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/home', function () { return redirect('/dashboard'); })->name('home');

    // Terminal
    Route::get('/terminal', [MessageController::class, 'index'])->name('terminal.index');
    Route::post('/terminal', [MessageController::class, 'store'])->name('terminal.store');

    // Leads
    Route::resource('leads', LeadController::class);
    Route::post('/leads/{lead}/notes', [LeadController::class, 'addNote'])->name('leads.addNote');
    Route::post('/leads/{lead}/mark-contacted', [LeadController::class, 'markContacted'])->name('leads.markContacted');
    Route::post('/leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Admin Governance
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/provision', [AdminController::class, 'provision'])->name('provision');
        Route::post('/provision/store', [AdminController::class, 'provisionStaff'])->name('provisionStaff');
        
        // Match the {id} parameter used in AdminController findOrFail
        Route::put('/staff/{id}/update', [AdminController::class, 'updateStaff'])->name('updateStaff');
        Route::delete('/staff/{id}/destroy', [AdminController::class, 'destroyStaff'])->name('destroyStaff');
    });
});