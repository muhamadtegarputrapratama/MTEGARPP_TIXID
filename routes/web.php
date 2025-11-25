<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\TicketController;
use App\Models\Schedule;

Route::get('/', [MovieController::class, 'home'])->name('home');
Route::get('/movies/active', [MovieController::class, 'homeMovies'])->name('home.movies.active');
Route::get('/schedules/{movie_id}', [MovieController::class, 'movieSchedule'])->name('schedules.detail');

Route::middleware('isUser')->group(function() {
Route::get('/schedules/{scheduleId}/hours/{hourId}/ticket', [TicketController::class, 'showSeats'])->name('schedules.show_seats');
Route::prefix('/tickets')->name('tickets.')->group(function() {
Route::post('/', [TicketController::class, 'store'])->name('store');
Route::get('/{ticketId}/order', [TicketController::class, 'ticketOrderPage'])->name('order.page');
//pembuatan barcode pembayaran
Route::post('/barcode', [TicketController::class, 'createBarcode'])->name('barcode');
//halaman yang menampilkan barcode
Route::get('/{ticketId}/payment', [TicketController::class, 'ticketPaymentPage'])->name('payment.page');
Route::patch('{ticketId}/payment/update', [TicketController::class, 'updateStatusTicket'])->name('payment.update');
Route::get('/{ticketId}/show', [TicketController::class, 'show'])->name('show');
Route::get('/{ticketId}/export/pdf', [TicketController::class, 'exportPdf'])->name('export.pdf');
Route::get('/list', [TicketController::class, 'index'])->name('index');
});
});

//menu bioskop pada navbar user
Route::get('/cinemas/list', [CinemaController::class, 'cinemaList'])->name('cinemas.list');
Route::get('/cinemas/{cinema_id}/schedules', [CinemaController::class, 'cinemaSchedules'])->name('cinemas.schedules');


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

Route::post(
    '/signup',
    [UserController::class, 'register']
)
    ->name('signup.register');

Route::post('/signup', [UserController::class, 'register'])->name('signup.register');
Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');


//dashboard di pindahkan ke grup middleware agar bisa digunakan middlewarenya
Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function() {
    Route::get('/tickets/chart', [TicketController::class, 'dataChart'])->name('tickets.chart');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');


    Route::prefix('/cinemas')->name('cinemas.')->group(function() {
        Route::get('/datatables', [CinemaController::class, 'datatables'])->name('datatables');
        Route::get('/', [CinemaController::class, 'index'])->name('index');
        Route::get('/create', [CinemaController::class, 'create'])->name('create');
        Route::post('/store', [CinemaController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CinemaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CinemaController::class, 'destroy'])->name('delete');
        Route::get('/export', [CinemaController::class, 'exportExcel'])->name('export');
        Route::get('/trash', [CinemaController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [CinemaController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [CinemaController::class, 'deletePermanent'])->name('delete_permanent');
        Route::patch('/active/{id}', [CinemaController::class, 'active'])->name('actived');
        Route::get('/{id}', [CinemaController::class, 'show'])->name('show');
    });

    Route::prefix('/movies')->name('movies.')->group(function() {
        Route::get('/chart', [MovieController::class, 'chart'])->name('chart');
        Route::get('/datatables', [MovieController::class, 'datatables'])->name('datatables');
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/store', [MovieController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [MovieController::class, 'destroy'])->name('destroy');
        Route::patch('/active/{id}', [MovieController::class, 'actived'])->name('actived');
        Route::get('/export', [MovieController::class, 'exportExcel'])->name('export');
        Route::get('/trash', [MovieController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [MovieController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [MovieController::class, 'deletePermanent'])->name('delete_permanent');
    });

    Route::prefix('/admin')->name('users.')->group(function() {
        Route::get('/users/datatables', [UserController::class, 'datatables'])->name('datatables');
        Route::get('/users', [UserController::class, 'index'])->name('index');
        Route::get('/users/create', [UserController::class, 'create'])->name('create');
        Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/users/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('destroy');
        // Route::resource('users', UserController::class);
        Route::post('/users/store', [UserController::class, 'store'])->name('store');
        Route::get('/export', [UserController::class, 'exportExcel'])->name('export');
        Route::get('/users/trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/users/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/users/delete-permanent/{id}', [UserController::class, 'deletePermanent'])->name('delete_permanent');
    });
});

// Route::prefix('/admin')->name('admin.')->group(function() {
    Route::get('/users/staff', [UserController::class, 'staff'])->name('users.staff');
// });

// Route::resource('users', UserController::class, ['as' => 'admin']);


Route::get('/users/create', function () {
    return view('admin.user.create2');
})->name('admin.users.create2');

Route::get('/users/edit', function () {
    return view('admin.user.edit2');
})->name('admin.users.edit2');

Route::prefix('/staff')->name('staff.')->group(function() {
    Route::get('/dashboard', function() {
        return view('staff.dashboard');  })->name('dashboard');

Route::middleware('isStaff')->prefix('/staff')->name('staff.')->group(function() {
    Route::get('/index', function() {
        return view('promo.index');
    })->name('index');

    Route::prefix('/schedules')->name('schedules.')->group(function() {
        Route::get('/datatables', [ScheduleController::class, 'datatables'])->name('datatables');
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::post('store', [ScheduleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ScheduleController::class, 'edit'])->name('edit');
        Route::patch('/update/{id}', [ScheduleController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('delete');
        Route::get('/trash', [ScheduleController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [ScheduleController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/export', [ScheduleController::class, 'exportExcel'])->name('export');

    });
});

Route::prefix('/promo')->name('promo.')->group(function() {
    Route::get('/datatables', [PromoController::class, 'datatables'])->name('datatables');
    Route::get('/', [PromoController::class, 'index'])->name('index');
    Route::get('/create', [PromoController::class, 'create'])->name('create');
    Route::post('/', [PromoController::class, 'store'])->name('store');
    Route::get('/{promo}/edit', [PromoController::class, 'edit'])->name('edit');
    Route::put('/{promo}', [PromoController::class, 'update'])->name('update');
    Route::delete('/{promo}', [PromoController::class, 'destroy'])->name('destroy');
    Route::get('/export', [PromoController::class, 'exportExcel'])->name('export');
    Route::get('/trash', [PromoController::class, 'trash'])->name('trash');
    Route::patch('/restore/{id}', [PromoController::class, 'restore'])->name('restore');
    Route::delete('/delete-permanent/{id}', [PromoController::class, 'deletePermanent'])->name('delete_permanent');
});
});


//http methods
//get = nampilin data
//post = nambah data
//put = update data
//delete = hapus data
//patch = update data sebagian
//options = ngambil informasi tentang resource
//head = ngambil header aja ga usah body
