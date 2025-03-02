<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\PublikController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/server-time', function () {
    return response()->json(['server_time' => now()->toDateTimeString()]);
});

Route::get('/', [PublikController::class, 'coming'])->name('home.publik');

// Rute Admin
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dash');
    Route::get('/admin/profile/edit', [AdminController::class, 'editProf'])->name('prof.edit');
    Route::post('/admin/profile/updateProfile', [AdminController::class, 'updateProf'])->name('prof.update');
    Route::get('/admin/profile/editPass', [AdminController::class, 'editPass'])->name('prof.edit.pass');
    Route::post('/admin/profile/updatePass', [AdminController::class, 'updatePass'])->name('prof.update.pass');

    Route::get('/admin/program', [ProgramController::class, 'index'])->name('program.data');
    Route::get('/admin/program/add', [ProgramController::class, 'create'])->name('program.add');
    Route::post('/admin/program/store', [ProgramController::class, 'store'])->name('program.store');
    Route::get('/admin/program/edit/{id}', [ProgramController::class, 'edit'])->name('program.edit');
    Route::post('/admin/program/update/{id}', [ProgramController::class, 'update'])->name('program.update');
    Route::get('/admin/program/delete/{id}', [ProgramController::class, 'destroy'])->name('program.delete');

    // Route::get('/admin/periode', [PeriodeController::class, 'index'])->name('periode.data');
    // Route::get('/admin/periode/add', [PeriodeController::class, 'create'])->name('periode.add');
    // Route::post('/admin/periode/store', [PeriodeController::class, 'store'])->name('periode.store');
    // Route::get('/admin/periode/edit/{id}', [PeriodeController::class, 'edit'])->name('periode.edit');
    // Route::get('/admin/periode/aktif/{id}', [PeriodeController::class, 'aktif'])->name('periode.aktif');
    // Route::get('/admin/periode/nonaktif/{id}', [PeriodeController::class, 'nonaktif'])->name('periode.nonaktif');
    // Route::post('/admin/periode/update/{id}', [PeriodeController::class, 'update'])->name('periode.update');
    // Route::get('/admin/periode/delete/{id}', [PeriodeController::class, 'destroy'])->name('periode.delete');

    // Route::get('/admin/order', [OrderController::class, 'index'])->name('order.data');
    // Route::get('/admin/order/add', [OrderController::class, 'create'])->name('order.add');
    // Route::post('/admin/order/store', [OrderController::class, 'store'])->name('order.store');
    // Route::get('/admin/order/edit/{id}', [OrderController::class, 'edit'])->name('order.edit');
    // Route::post('/admin/order/update/{id}', [OrderController::class, 'update'])->name('order.update');
    // Route::get('/admin/order/delete/{id}', [OrderController::class, 'destroy'])->name('order.delete');

    // Route::get('/admin/payment', [PaymentController::class, 'index'])->name('payment.data');
    // Route::get('/admin/payment/detail/{id}', [PaymentController::class, 'show'])->name('payment.detail');
    // Route::post('/admin/payment/update/{id}', [PaymentController::class, 'update'])->name('payment.update');
    // Route::get('/admin/payment/delete/{id}', [PaymentController::class, 'destroy'])->name('payment.delete');

    Route::get('/admin/user', [UserController::class, 'index'])->name('user.data');
    Route::get('/admin/user/add', [UserController::class, 'create'])->name('user.add');
    Route::post('/admin/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/admin/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/admin/user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/admin/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
    Route::get('/admin/user/resetPass/{id}', [UserController::class, 'resetPass'])->name('user.resetpass');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
