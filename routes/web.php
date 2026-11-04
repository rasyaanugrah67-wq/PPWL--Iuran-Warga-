<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\UserController;

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
// Rute yang hanya bisa dibuka oleh pengguna yang sudah LOGIN dan berlevel ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Contoh rute khusus admin
    Route::get('/admin/users', function () {
        return "Halaman Kelola Data User Khusus Admin";
    })->name('admin.users');
    
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});
});
Route::get('/iuran', [IuranController::class, 'index'])->name('iuran.index');
Route::get('/iuran/create', [App\Http\Controllers\IuranController::class, 'create'])->name('iuran.create');
Route::post('/iuran', [App\Http\Controllers\IuranController::class, 'store'])->name('iuran.store');
Route::get('/iuran/{id}/edit', [App\Http\Controllers\IuranController::class, 'edit'])->name('iuran.edit');
Route::post('/iuran/{id}', [App\Http\Controllers\IuranController::class, 'update'])->name('iuran.update');
Route::post('/iuran/delete/{id}', [App\Http\Controllers\IuranController::class, 'destroy'])->name('iuran.destroy');
Route::get('/iuran/search', [App\Http\Controllers\IuranController::class, 'search'])->name('iuran.search');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
