<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\PublikController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/server-time', function () {
    return response()->json(['server_time' => now()->toDateTimeString()]);
});

// Route::get('/', [PublikController::class, 'coming'])->name('home.publik');
Route::get('/coming', [PublikController::class, 'coming'])->name('coming.publik');

// Rute Admin
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dash');
    Route::get('/profile/edit', [AdminController::class, 'editProf'])->name('prof.edit');
    Route::post('/profile/updateProfile', [AdminController::class, 'updateProf'])->name('prof.update');
    Route::get('/profile/editPass', [AdminController::class, 'editPass'])->name('prof.edit.pass');
    Route::post('/profile/updatePass', [AdminController::class, 'updatePass'])->name('prof.update.pass');

    Route::get('/program', [ProgramController::class, 'index'])->name('program.data');

    Route::middleware(['finance'])->group(function () {
        Route::get('/program/add', [ProgramController::class, 'create'])->name('program.add');
        Route::post('/program/store', [ProgramController::class, 'store'])->name('program.store');
        Route::get('/program/edit/{id}', [ProgramController::class, 'edit'])->name('program.edit');
        Route::post('/program/update/{id}', [ProgramController::class, 'update'])->name('program.update');
        Route::get('/program/delete/{id}', [ProgramController::class, 'destroy'])->name('program.delete');

        Route::get('/transaksi/kas', [FinanceController::class, 'kas_data'])->name('kas.data');
        Route::get('/transaksi/kas/add', [FinanceController::class, 'kas_create'])->name('kas.add');

        Route::get('/transfer', [TransferController::class, 'index'])->name('transfer.data');
        Route::get('/transfer/add', [TransferController::class, 'create'])->name('transfer.add');
        Route::post('/transfer/store', [TransferController::class, 'store'])->name('transfer.store');
        Route::get('/transfer/edit/{id}', [TransferController::class, 'edit'])->name('transfer.edit');
        Route::post('/transfer/update/{id}', [TransferController::class, 'update'])->name('transfer.update');
        Route::get('/transfer/delete/{id}', [TransferController::class, 'destroy'])->name('transfer.delete');
    });

    // Route::get('/saldo', [FinanceController::class, 'index'])->name('saldo.data');

    Route::middleware(['super-user'])->group(function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.data');
        Route::get('/user/add', [UserController::class, 'create'])->name('user.add');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::get('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
        Route::get('/user/resetPass/{id}', [UserController::class, 'resetPass'])->name('user.resetpass'); 
    });
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
