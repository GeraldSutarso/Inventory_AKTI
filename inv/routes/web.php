<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\OverviewController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ProductSuppliesController;
use App\Http\Controllers\Dashboard\SupplierController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\QRScannerController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ExportPDFController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [OverviewController::class, 'index']);

    // Barang (Products)
    Route::prefix('barang')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('barang.index');
        Route::get('/create', [ProductController::class, 'create'])->name('barang.create');
        Route::post('/', [ProductController::class, 'store'])->name('barang.store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('barang.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('barang.update');
        Route::delete('/{product}', [ProductController::class, 'delete'])->name('barang.delete');
    });

    Route::get('/products', [ProductController::class, 'getAllProducts']);
    Route::get('/excel/products', [ProductController::class, 'exportExcel']);
    Route::get('/generate-qr/{product}', [ProductController::class, 'generateQR'])->name('products.qr');
    Route::get('/hasil/{product}', [ProductController::class, 'showHasil'])->name('hasil.show');
    Route::get('/download-qr/{product}', [ProductController::class, 'downloadQR'])->name('products.qr.download');

    // Supplier
    Route::get('/supplier', [SupplierController::class, 'index']);
    Route::get('/input-supplier', [SupplierController::class, 'create']);
    Route::post('/input-supplier', [SupplierController::class, 'store']);
    Route::get('/ubah-supplier/{id}', [SupplierController::class, 'edit']);
    Route::post('/ubah-supplier/{id}', [SupplierController::class, 'update']);
    Route::delete('/hapus-supplier/{id}', [SupplierController::class, 'delete']);
    Route::get('/suppliers', [SupplierController::class, 'getAllSuppliers']);
    Route::get('/excel/suppliers', [SupplierController::class, 'exportExcel']);

    // Kategori
    Route::get('/kategori', [CategoryController::class, 'index']);
    Route::get('/input-kategori', [CategoryController::class, 'create']);
    Route::post('/input-kategori', [CategoryController::class, 'store']);
    Route::get('/ubah-kategori/{id}', [CategoryController::class, 'edit']);
    Route::post('/ubah-kategori/{id}', [CategoryController::class, 'update']);
    Route::delete('/hapus-kategori/{id}', [CategoryController::class, 'delete']);
    Route::get('/excel/kategori', [CategoryController::class, 'exportExcel']);

    // Admin & Petugas
    Route::middleware('role:admin')->group(function () {
        // Admin
        Route::get('/admin', [UserController::class, 'admin']);
        Route::get('/input-admin', [UserController::class, 'createAdmin']);
        Route::post('/input-admin', [UserController::class, 'storeAdmin']);
        Route::get('/ubah-admin/{id}', [UserController::class, 'editAdmin']);
        Route::post('/ubah-admin/{id}', [UserController::class, 'updateAdmin']);
        Route::delete('/hapus-admin/{id}', [UserController::class, 'delete']);

        // Petugas
        Route::get('/petugas', [UserController::class, 'officer']);
        Route::get('/input-petugas', [UserController::class, 'createOfficer']);
        Route::post('/input-petugas', [UserController::class, 'storeOfficer']);
        Route::get('/ubah-petugas/{id}', [UserController::class, 'editOfficer']);
        Route::post('/ubah-petugas/{id}', [UserController::class, 'updateOfficer']);
        Route::delete('/hapus-petugas/{id}', [UserController::class, 'delete']);

        // Upload TTD
        Route::post('/admin/upload-ttd', [UserController::class, 'uploadTtd'])->name('admin.upload.ttd');

        // Kepala Unit
        Route::get('/input-kepala', [UserController::class, 'createHead']);
        Route::post('/input-kepala', [UserController::class, 'storeHead']);
        Route::get('/ubah-kepala/{id}', [UserController::class, 'editHead']);
        Route::post('/ubah-kepala/{id}', [UserController::class, 'updateHead']);
        Route::delete('/hapus-kepala/{id}', [UserController::class, 'delete']);

        // Sarpras
        Route::get('/input-sarpras', [UserController::class, 'createSarpras']);
        Route::post('/input-sarpras', [UserController::class, 'storeSarpras']);
        Route::get('/ubah-sarpras/{id}', [UserController::class, 'editSarpras']);
        Route::post('/ubah-sarpras/{id}', [UserController::class, 'updateSarpras']);
        Route::delete('/hapus-sarpras/{id}', [UserController::class, 'deleteSarpras']);
    });

    // Kepala Unit dan Sarpras (Non-admin)
    Route::get('/kepala-unit', [UserController::class, 'head']);
    Route::get('/sarpras', [UserController::class, 'sarpras']);

    // Supplies
    Route::resource('supplies', ProductSuppliesController::class)
        ->except(['show'])
        ->parameters(['supplies' => 'supply']);

    // Logout
    Route::get('/logout', [AuthController::class, 'logout']);
});

// Guest (Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// QR & Label
Route::get('/qr-scanner', [QRScannerController::class, 'index'])->name('qr-scanner');
Route::get('/label', [LabelController::class, 'index'])->name('label.index');

// Produk Update & Export
Route::put('/product/update-stock/{id}', [ProductController::class, 'updateStock'])->name('updateStock');
Route::get('/export/products/pdf/{id}', [ProductController::class, 'exportPdf'])->name('export.products.pdf');

// Orders
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/review/{product}', [OrderController::class, 'review'])->name('orders.review');
Route::get('/orders/preview/{productId}', [OrderController::class, 'previewPdf'])->name('orders.previewPdf');
