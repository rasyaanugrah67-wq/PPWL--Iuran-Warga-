<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes([
    'register' => false,
    'reset' => false,
]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rute Iuran
Route::get('/iuran', [IuranController::class, 'index'])->name('iuran.index');
Route::get('/iuran/create', [IuranController::class, 'create'])->name('iuran.create');
Route::post('/iuran', [IuranController::class, 'store'])->name('iuran.store');
Route::get('/iuran/{id}/edit', [IuranController::class, 'edit'])->name('iuran.edit');
Route::post('/iuran/{id}', [IuranController::class, 'update'])->name('iuran.update');
Route::post('/iuran/delete/{id}', [IuranController::class, 'destroy'])->name('iuran.destroy');
Route::get('/iuran/search', [IuranController::class, 'search'])->name('iuran.search');
Route::get('/iuran/bayar/{id}', [App\Http\Controllers\IuranController::class, 'bayar'])->name('iuran.bayar');
Route::post('/iuran/bayar/{id}', [App\Http\Controllers\IuranController::class, 'proses_bayar'])->name('iuran.proses_bayar');

Route::middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/admin/users', function () {
        return "Halaman Kelola Data User Khusus Admin";
    })->name('admin.users');
    
    Route::resource('users', UserController::class);
    
    Route::resource('pembayaran', PembayaranController::class);

});     