<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\clientCarController;
use App\Http\Controllers\adminDashboardController;
use App\Http\Controllers\usersController;
use App\Http\Controllers\addNewAdminController;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\AdminAuth\LoginController;
use App\Http\Controllers\carSearchController;
use App\Http\Controllers\PaymentController;
use App\Models\User;
use App\Models\Car;

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
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', adminDashboardController::class)->name('adminDashboard');
    Route::resource('cars', CarController::class);

    // User Management
    Route::get('/users', function () {
        $admins = User::where('role', 'admin')->get();
        $clients = User::where('role', 'client')->paginate(5);
        return view('admin.users', compact('admins', 'clients'));
    })->name('users');
    Route::get('/userDetails/{user}', [usersController::class, 'show'])->name('userDetails');

    // Admin Management
    Route::get('/addAdmin', [usersController::class, 'create'])->name('addAdmin');
    Route::post('/addAdmin', [addNewAdminController::class, 'register'])->name('addNewAdmin');

    // Reservation & Payment Management
    Route::get('/updateReservation/{reservation}', [ReservationController::class, 'editStatus'])->name('editStatus');
    Route::put('/updateReservation/{reservation}', [ReservationController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/updatePayment/{reservation}', [ReservationController::class, 'editPayment'])->name('editPayment');
    Route::put('/updatePayment/{reservation}', [ReservationController::class, 'updatePayment'])->name('updatePayment');
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
