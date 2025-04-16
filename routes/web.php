<?php

use App\Http\Controllers\AdminFuelRequest\AdminUserController;
use App\Http\Controllers\AdminFuelRequest\DashboardController;
use App\Http\Controllers\AdminFuelRequest\InventoryController;
use App\Http\Controllers\AdminFuelRequest\HistoryController;
use App\Http\Controllers\AdminFuelRequest\AccountController;
use App\Http\Controllers\AdminFuelRequest\RegistryController;
use App\Http\Controllers\AdminFuelRequest\DocumentController;
use App\Http\Controllers\AdminFuelRequest\RequestController;
use App\Http\Controllers\AdminFuelRequest\LoginController;
use App\Http\Controllers\FuelConsumption\SidebarController;
use App\Http\Controllers\FuelConsumption\AdminRequestController;
use App\Http\Controllers\FuelConsumption\AdminInventoryController;
use App\Http\Controllers\FuelConsumption\AdminDriverController;
use App\Http\Controllers\FuelConsumption\AdminConsumptionController;
use App\Http\Controllers\FuelConsumption\AdminTeamController;
use App\Http\Controllers\FuelConsumption\AdminOverviewController;
use App\Http\Controllers\ThemeController;
use App\Http\Middleware\AdminAuthentication;

use Illuminate\Support\Facades\Route;

// Login routes (public)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('logout');

// All protected admin routes - grouped under authentication middleware
Route::middleware([AdminAuthentication::class])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::post('/upload-image', [DashboardController::class, 'uploadImage'])->name('upload.image');
    
    // Inventory Routes
    Route::get('/inventory', [InventoryController::class, 'showInventory'])->name('inventory');
    Route::post('/inventory/withdrawal-details', [InventoryController::class, 'getWithdrawalDetails']);
    Route::post('/inventory/add-stock', [InventoryController::class, 'addStock'])->name('inventory.add-stock');
    Route::post('/products', [InventoryController::class, 'addProduct'])->name('products.add');
    Route::post('/admin/inventory/delete-product', [InventoryController::class, 'deleteProduct'])->name('inventory.delete-product');
    
    // History Routes
    Route::get('/history', [HistoryController::class, 'showHistory'])->name('history');
    Route::get('/history/data', [HistoryController::class, 'getHistoryData'])->name('history.data');
    
    // Account Routes
    Route::get('/account', [AccountController::class, 'showAccount'])->name('account');
    Route::post('/reset-password', [AccountController::class, 'resetPassword'])->name('reset.password');
    
    // Registry Routes
    Route::get('/registry', [RegistryController::class, 'showRegistry'])->name('registry');
    Route::post('/store-driver', [RegistryController::class, 'storeDriver'])->name('drivers.store');
    Route::post('/store-vehicle', [RegistryController::class, 'storeVehicle'])->name('vehicle.store');
    
    // Document Routes
    Route::get('/document', [DocumentController::class, 'showDocument'])->name('document');
    
    // Request Routes
    Route::get('/request', [RequestController::class, 'showRequest'])->name('request');
    Route::post('/request', [RequestController::class, 'storeRequest'])->name('request.store');
    
    // Admin Request Routes
    Route::get('/admin-request', [AdminRequestController::class, 'adminRequest'])->name('admin-request');
    Route::get('/get-drivers', [AdminRequestController::class, 'getDrivers'])->name('get.drivers');
    Route::get('/request-details/{id}', [AdminRequestController::class, 'getRequestDetails'])->name('request.details');
    Route::post('/update-request-status/{id}', [AdminRequestController::class, 'updateRequestStatus']);
    Route::get('/edit-request/{id}', [AdminRequestController::class, 'editRequest']);
    Route::post('/update-request-details/{id}', [AdminRequestController::class, 'updateRequestDetails']);
    Route::delete('/admin/requests/delete/{id}', [AdminRequestController::class, 'deleteRequest'])->name('admin.request.delete');
    Route::get('/get-dropdown-options', [AdminRequestController::class, 'getDropdownOptions']);
    Route::post('/admin/request/download', [AdminRequestController::class, 'downloadRequests'])->name('admin.request.download');
    Route::post('/save-theme-preference', [ThemeController::class, 'saveThemePreference'])->name('save-theme-preference');
    
    // Admin Inventory Routes
    Route::get('/admin-inventory', [AdminInventoryController::class, 'adminInventory'])->name('admin-inventory');
    Route::post('/admin/inventory/add-product', [AdminInventoryController::class, 'addProduct'])->name('admin.inventory.addProduct');
    Route::delete('/admin/inventory/delete-product/{id}', [AdminInventoryController::class, 'deleteProduct'])->name('admin.inventory.delete');
    Route::post('/admin/inventory/withdrawal-details', [AdminInventoryController::class, 'getWithdrawalDetails'])->name('admin.inventory.withdrawalDetails');
    
    // Admin inventory product routes
    Route::prefix('admin/inventory')->group(function () {
        Route::get('/products', [AdminInventoryController::class, 'getProducts']);
        Route::get('/products/{id}/stock', [AdminInventoryController::class, 'getProductStock']);
        Route::post('/stock/add', [AdminInventoryController::class, 'addStock']);
    });
    // Admin Driver Routes
    Route::get('/admin-driver', [AdminDriverController::class, 'adminDriverList'])->name('admin-driver');
    Route::post('admin/driver/store', [AdminDriverController::class, 'store'])->name('admin.driver.store');
    Route::delete('/admin/driver/delete/{id}', [AdminDriverController::class, 'deleteDriver'])->name('admin.driver.delete');});
    Route::get('/admin/driver/{id}/consumption', [AdminDriverController::class, 'getDriverConsumption'])->name('admin.driver.consumption');
    Route::get('/admin/driver/{driverId}/product/{productId}/requests', [AdminDriverController::class, 'getProductRequests'])->name('admin.driver.product.requests');
    Route::get('/admin/driver/{id}/edit', [AdminDriverController::class, 'edit'])->name('admin.driver.edit');
    Route::put('/admin/driver/{id}', [AdminDriverController::class, 'update'])->name('admin.driver.update');
    // Admin Consumption Routes
    Route::get('/admin-consumption', [AdminConsumptionController::class, 'adminConsumption'])->name('admin-consumption');
// Admin Overview Routes
    Route::get('/admin-overview', [AdminOverviewController::class, 'adminOverview'])->name('admin-overview');
    Route::post('/carousel/upload', [AdminOverviewController::class, 'uploadCarouselImage'])->name('carousel.upload');
    Route::post('/carousel/update', [AdminOverviewController::class, 'update'])->name('carousel.update');
    Route::post('/carousel/delete', [AdminOverviewController::class, 'delete'])->name('carousel.delete');    
    // Fuel Usage Data for Chart - AJAX endpoint
    Route::get('/admin/fuel-usage-data', [AdminOverviewController::class, 'getFuelUsageData'])->name('admin.fuel-usage-data');
    // Admin Team Routes
    Route::get('/admin-team', [AdminTeamController::class, 'adminTeam'])->name('admin-team');