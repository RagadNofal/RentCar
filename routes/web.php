<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\clientCarController;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\AdminAuth\LoginController;
use App\Http\Controllers\carSearchController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Models\Car;
use Illuminate\Support\Facades\Log;

// ------------------- Guest Routes --------------------------------------- //
Route::get('/', function () {
    $cars = Car::take(6)->where('status', '=', 'available')->get();
    return view('home', compact('cars'));
})->name('home');

Route::get('/cars', [clientCarController::class, 'index'])->name('cars');
Route::get('/cars/search', [carSearchController::class, 'search'])->name('carSearch');

Route::get('location', fn() => view('location'))->name('location');
Route::get('contact_us', fn() => view('contact_us'))->name('contact_us');

Route::get('/privacy_policy', fn() => view('Privacy_Policy'))->name('privacy_policy');
Route::get('/terms_conditions', fn() => view('Terms_Conditions'))->name('terms_conditions');


  
// ------------------- Admin Auth Routes ---------------------------------- //
Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
Route::redirect('/admin', 'admin/login');

// ------------------- Admin Routes --------------------------------------- //
// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart/reservations', [App\Http\Controllers\Admin\DashboardController::class, 'reservationStatusByWeek'])
     ->name('dashboard.chart.reservations');
    Route::get('/dashboard/funnel', [App\Http\Controllers\Admin\DashboardController::class, 'reservationFunnel'])->name('dashboard.funnel');
    Route::get('/dashboard/timeline', [ReservationController::class, 'reservationTimeline'])->name('reservations.timeline');



    // Car management routes
    //Route::delete('cars/{car}', [CarController::class, 'destroy'])->name('destroy');
    Route::resource('cars', App\Http\Controllers\Admin\CarController::class);
    Route::get('cars/{car}/rental-history', [App\Http\Controllers\Admin\CarController::class, 'rentalHistory'])->name('cars.rental-history');
    


    // Reservation management routes
    Route::resource('reservations', App\Http\Controllers\Admin\ReservationController::class);

    // User management routes
   Route::resource('users', App\Http\Controllers\Admin\UserController::class);

});

// ------------------- Client Routes -------------------------------------- //

Route::middleware(['auth', 'restrictAdminAccess'])->group(function () {
    Route::get('/reservations/{car}', [ReservationController::class, 'create'])->name('car.reservation');
    Route::post('/reservations/{car}', [ReservationController::class, 'store'])->name('car.reservationStore');
    
    Route::get('/payment/{reservation}', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/{reservation}', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/thank-you/{reservation}', [PaymentController::class, 'thankYou'])->name('payment.thankyou');

    Route::get('/reservations', [ReservationController::class, 'myReservations'])->name('clientReservation');
    Route::get('invoice/{reservation}', [invoiceController::class, 'invoice'])->name('invoice');
    Route::post('/client/reservations/{reservation}/payment', [ReservationController::class, 'processPayment'])
    ->name('client.reservations.processPayment');
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
});
// // ------------------- Shared (Auth) Routes ------------------------------- //
// Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');


Auth::routes();
