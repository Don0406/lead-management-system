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
| Public Routes (Accessible by Anyone)
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('welcome'); })->name('landing');
Route::get('/capabilities/{slug}', [PublicController::class, 'showService'])->name('services.show');
Route::post('/inquiry', [LeadController::class, 'storePublic'])->name('landing.store');
Route::get('/directory/{slug}', [DirectoryController::class, 'show'])->name('directory.show');

// Governance & Legal (Publicly Accessible)
Route::get('/terms', [PublicController::class, 'terms'])->name('legal.terms');
Route::get('/privacy', [PublicController::class, 'privacy'])->name('legal.privacy');
Route::get('/cookies', [PublicController::class, 'cookies'])->name('legal.cookies');
Route::get('/compliance', [PublicController::class, 'compliance'])->name('legal.compliance');

Auth::routes();

/*
|--------------------------------------------------------------------------
| Secure Dashboard Routes (Requires Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Main Hub
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/home', function () { return redirect('/dashboard'); })->name('home');
    
    // Staff Management (From Dashboard Modal)
    Route::post('/staff/provision', [HomeController::class, 'provisionStaff'])->name('staff.provision');

    // Mail Terminal (Communication)
    Route::get('/terminal', [MessageController::class, 'index'])->name('terminal.index');
    Route::post('/terminal', [MessageController::class, 'store'])->name('terminal.store');

    // Intelligence Ledger (Leads)
    Route::resource('leads', LeadController::class);
    Route::post('/leads/{lead}/notes', [LeadController::class, 'addNote'])->name('leads.addNote');
    Route::post('/leads/{lead}/mark-contacted', [LeadController::class, 'markContacted'])->name('leads.markContacted');
    Route::post('/leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');

    // Identity & Security (Profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Marketplace & Transactional (Orders)
    Route::get('/portal/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/portal/orders/initialize', [OrderController::class, 'store'])->name('orders.store');
    Route::patch('/portal/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    /*
    |--------------------------------------------------------------------------
    | Admin Governance (Requires Admin Role)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/provision', [AdminController::class, 'provision'])->name('provision');
        Route::post('/provision/store', [AdminController::class, 'provisionStaff'])->name('provisionStaff');
        Route::put('/staff/{id}/update', [AdminController::class, 'updateStaff'])->name('updateStaff');
        Route::delete('/staff/{id}/destroy', [AdminController::class, 'destroyStaff'])->name('destroyStaff');
    });

});