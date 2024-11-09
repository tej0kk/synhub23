<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\customer\BannerController as CustomerBannerController;
use App\Http\Controllers\customer\BayarController as CustomerBayarController;
use App\Http\Controllers\customer\PesananController as CustomerPesananController;
use App\Http\Controllers\customer\ProdukController as CustomerProdukController;
use App\Http\Controllers\dashboar\BannerController as DashboarBannerController;
use App\Http\Controllers\dashboar\BayarController as DashboarBayarController;
use App\Http\Controllers\dashboar\PesananController as DashboarPesananController;
use App\Http\Controllers\dashboar\ProdukController as DashboarProdukController;
use App\Http\Controllers\dashboar\UserController as DashboarUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/unauthenticate', [AuthController::class, 'unauthenticate'])->name('unauthenticate');

Route::prefix('customer')->group(function () {
    Route::post('/register', [AuthController::class, 'register'], ['as', 'customer']);
    Route::get('/banner', CustomerBannerController::class, ['as', 'customer']);
    Route::get('/produk', [CustomerProdukController::class, 'index'], ['as', 'customer']);
    Route::get('/bayar', CustomerBayarController::class, ['as', 'customer'])->middleware(['auth:sanctum', 'checkrole:customer']);
    Route::get('/produk/{slug}', [CustomerProdukController::class, 'show'], ['as', 'customer'])->middleware(['auth:sanctum', 'checkrole:customer']);
    Route::get('/pesanan', [CustomerPesananController::class, 'index'], ['as', 'customer'])->middleware(['auth:sanctum', 'checkrole:customer']);
    Route::post('/pesanan', [CustomerPesananController::class, 'store'], ['as', 'customer'])->middleware(['auth:sanctum', 'checkrole:customer']);
    Route::post('upload-bukti-pesanan', [CustomerPesananController::class, 'uploadBukti'], ['as', 'customer'])->middleware(['auth:sanctum', 'checkrole:customer']);
    Route::get('/pesanan/{kode_pesanan}', [CustomerPesananController::class, 'show'], ['as', 'customer'])->middleware(['auth:sanctum', 'checkrole:customer']);
    Route::get('/logout', [AuthController::class, 'logout'], ['as', 'customer'])->middleware(['auth:sanctum', 'checkrole:customer']);
});

Route::prefix('dashboard')->group(function () {
    Route::get('/banner/ubah-status/{banner}', [DashboarBannerController::class, 'ubahStatus'], ['as', 'dashboard']);
    Route::get('/bayar/ubah-status/{bayar}', [DashboarBayarController::class, 'ubahStatus'], ['as', 'dashboard']);
    Route::get('/pesanan/ubah-status/{pesanan}', [DashboarPesananController::class, 'ubahStatus'], ['as', 'dashboard']);
    Route::resource('/banner', DashboarBannerController::class, ['as', 'dashboard'])->except('create', 'edit');
    Route::resource('/pesanan', DashboarPesananController::class, ['as', 'dashboard'])->except('create', 'store', 'update', 'edit', 'destroy');
    Route::resource('/produk', DashboarProdukController::class, ['as', 'dashboard'])->except('create', 'edit');
    Route::resource('/bayar', DashboarBayarController::class, ['as', 'dashboard'])->except('create', 'edit');
    Route::resource('/user', DashboarUserController::class, ['as', 'dashboard'])->except('create', 'edit');
});
