<?php

use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AdminUserController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminUserController::class, 'login']);
Route::get('/logout', [AdminUserController::class, 'logout'])->name('logout');
Route::get('/dashboard', [AdminUserController::class, 'showDashboard'])->name('dashboard');
Route::get('/document', [AdminUserController::class, 'showDocument'])->name('document');
Route::get('/request', [AdminUserController::class, 'showRequest'])->name('request');
Route::post('/request', [AdminUserController::class, 'storeRequest'])->name('storeRequest');
Route::get('/registry', [AdminUserController::class, 'showRegistry'])->name('registry');
Route::post('/drivers', [AdminUserController::class, 'storeDriver'])->name('drivers.store');
Route::post('/vehicle', [AdminUserController::class, 'storeVehicle'])->name('vehicle.store');
Route::get('/account', [AdminUserController::class, 'showAccount'])->name('account');
Route::post('/reset-password', [AdminUserController::class, 'resetPassword'])->name('reset.password');
Route::get('/inventory', [AdminUserController::class, 'showInventory'])->name('inventory');
Route::get('/get-inventory-data', [AdminUserController::class, 'getInventoryData']);