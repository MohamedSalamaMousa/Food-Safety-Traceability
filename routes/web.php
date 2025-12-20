<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RawMeatBatchController;
use App\Http\Controllers\ProcessingBatchController;
use App\Http\Controllers\CookingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ComplaintController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

// Supplier Routes
Route::resource('suppliers', SupplierController::class);

// Raw Meat Batch Routes
Route::resource('raw-meat-batches', RawMeatBatchController::class);
Route::post('raw-meat-batches/{raw_meat_batch}/log-storage', [RawMeatBatchController::class, 'logStorage'])
    ->name('raw-meat-batches.log-storage');

// Processing Batch Routes
Route::resource('processing-batches', ProcessingBatchController::class);

// Cooking Routes
Route::resource('cooking', CookingController::class);

// Order Routes
Route::resource('orders', OrderController::class);

// Complaint Routes (Most Important - Full Traceability)
Route::resource('complaints', ComplaintController::class);

// Export Routes
Route::get('export/complaint/{id}', [App\Http\Controllers\ExportController::class, 'exportComplaintTraceability'])->name('export.complaint');
