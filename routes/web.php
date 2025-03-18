<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\PublikController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/server-time', function () {
    return response()->json(['server_time' => now()->toDateTimeString()]);
});

// Route::get('/', [PublikController::class, 'coming'])->name('home.publik');

// Rute Admin
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dash');
    Route::get('/profile/edit', [AdminController::class, 'editProf'])->name('prof.edit');
    Route::post('/profile/updateProfile', [AdminController::class, 'updateProf'])->name('prof.update');
    Route::get('/profile/editPass', [AdminController::class, 'editPass'])->name('prof.edit.pass');
    Route::post('/profile/updatePass', [AdminController::class, 'updatePass'])->name('prof.update.pass');

    Route::get('/program', [ProgramController::class, 'index'])->name('program.data');
    Route::get('/program/add', [ProgramController::class, 'create'])->name('program.add');
    Route::post('/program/store', [ProgramController::class, 'store'])->name('program.store');
    Route::get('/program/edit/{id}', [ProgramController::class, 'edit'])->name('program.edit');
    Route::post('/program/update/{id}', [ProgramController::class, 'update'])->name('program.update');
    Route::get('/program/delete/{id}', [ProgramController::class, 'destroy'])->name('program.delete');

    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.data');
    Route::get('/employee/add', [EmployeeController::class, 'create'])->name('employee.add');
    Route::post('/employee/store', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::get('/employee/aktif/{id}', [EmployeeController::class, 'aktif'])->name('employee.aktif');
    Route::get('/employee/nonaktif/{id}', [EmployeeController::class, 'nonaktif'])->name('employee.nonaktif');
    Route::post('/employee/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::get('/employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.delete');

    Route::get('/in', [PemasukanController::class, 'index'])->name('in.data');
    Route::get('/in/add', [PemasukanController::class, 'create'])->name('in.add');
    Route::post('/in/store', [PemasukanController::class, 'store'])->name('in.store');
    Route::get('/in/edit/{id}', [PemasukanController::class, 'edit'])->name('in.edit');
    Route::post('/in/update/{id}', [PemasukanController::class, 'update'])->name('in.update');
    Route::get('/in/delete/{id}', [PemasukanController::class, 'destroy'])->name('in.delete');

    Route::get('/out', [PengeluaranController::class, 'index'])->name('out.data');
    Route::get('/out/add', [PengeluaranController::class, 'create'])->name('out.add');
    Route::post('/out/store', [PengeluaranController::class, 'store'])->name('out.store');
    Route::get('/out/edit/{id}', [PengeluaranController::class, 'edit'])->name('out.edit');
    Route::post('/out/update/{id}', [PengeluaranController::class, 'update'])->name('out.update');
    Route::get('/out/delete/{id}', [PengeluaranController::class, 'destroy'])->name('out.delete');

    Route::get('/saldo', [FinanceController::class, 'index'])->name('saldo.data');
    // Route::get('/saldo/add', [FinanceController::class, 'create'])->name('saldo.add');
    // Route::post('/saldo/store', [FinanceController::class, 'store'])->name('saldo.store');
    // Route::get('/saldo/edit/{id}', [FinanceController::class, 'edit'])->name('saldo.edit');
    // Route::post('/saldo/update/{id}', [FinanceController::class, 'update'])->name('saldo.update');
    // Route::get('/saldo/delete/{id}', [FinanceController::class, 'destroy'])->name('saldo.delete');

    Route::get('/user', [UserController::class, 'index'])->name('user.data');
    Route::get('/user/add', [UserController::class, 'create'])->name('user.add');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
    Route::get('/user/resetPass/{id}', [UserController::class, 'resetPass'])->name('user.resetpass');
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
