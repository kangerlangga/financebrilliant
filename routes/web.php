<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\PublikController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Models\Finance;
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
    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.data');

    Route::middleware(['finance'])->group(function () {
        Route::get('/get-saldo/{tabungan}', function ($tabungan) {
            $saldo = Finance::where('tabungan', $tabungan)->latest()->value('saldo_akhir');
            return response()->json(['saldo' => $saldo ?? 0]);
        });

        Route::get('/program/add', [ProgramController::class, 'create'])->name('program.add');
        Route::post('/program/store', [ProgramController::class, 'store'])->name('program.store');
        Route::get('/program/edit/{id}', [ProgramController::class, 'edit'])->name('program.edit');
        Route::post('/program/update/{id}', [ProgramController::class, 'update'])->name('program.update');
        Route::get('/program/delete/{id}', [ProgramController::class, 'destroy'])->name('program.delete');

        Route::get('/periode/add', [PeriodeController::class, 'create'])->name('periode.add');
        Route::post('/periode/store', [PeriodeController::class, 'store'])->name('periode.store');
        Route::get('/periode/edit/{id}', [PeriodeController::class, 'edit'])->name('periode.edit');
        Route::get('/periode/aktif/{id}', [PeriodeController::class, 'aktif'])->name('periode.aktif');
        Route::get('/periode/nonaktif/{id}', [PeriodeController::class, 'nonaktif'])->name('periode.nonaktif');
        Route::post('/periode/update/{id}', [PeriodeController::class, 'update'])->name('periode.update');
        Route::get('/periode/delete/{id}', [PeriodeController::class, 'destroy'])->name('periode.delete');

        Route::get('/transaksi', [FinanceController::class, 'index'])->name('trans.data');
        Route::get('/transaksi/add', [FinanceController::class, 'create'])->name('trans.add');
        Route::post('/transaksi/store', [FinanceController::class, 'store'])->name('trans.store');

        Route::get('/transfer', [TransferController::class, 'index'])->name('transfer.data');
        Route::get('/transfer/add', [TransferController::class, 'create'])->name('transfer.add');
        Route::post('/transfer/store', [TransferController::class, 'store'])->name('transfer.store');
        Route::get('/transfer/edit/{id}', [TransferController::class, 'edit'])->name('transfer.edit');
        Route::post('/transfer/update/{id}', [TransferController::class, 'update'])->name('transfer.update');
        Route::get('/transfer/delete/{id}', [TransferController::class, 'destroy'])->name('transfer.delete');

        Route::get('/tabungan', [TabunganController::class, 'index'])->name('tabungan.data');
        Route::get('/tabungan/add', [TabunganController::class, 'create'])->name('tabungan.add');
        Route::post('/tabungan/store', [TabunganController::class, 'store'])->name('tabungan.store');
        Route::get('/tabungan/edit/{id}', [TabunganController::class, 'edit'])->name('tabungan.edit');
        Route::post('/tabungan/update/{id}', [TabunganController::class, 'update'])->name('tabungan.update');
        Route::get('/tabungan/delete/{id}', [TabunganController::class, 'destroy'])->name('tabungan.delete');

        Route::get('/report/harian', [ReportController::class, 'harian'])->name('report.harian');
        Route::post('/report/harian', [ReportController::class, 'harian']);
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
