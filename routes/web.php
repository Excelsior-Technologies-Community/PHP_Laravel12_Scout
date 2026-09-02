<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'list'])
    ->name('products.list');

Route::get('/product/create', [ProductController::class, 'create'])
    ->name('products.create');

Route::post('/product/store', [ProductController::class, 'store'])
    ->name('products.store');

Route::get('/product/{id}', [ProductController::class, 'show'])
    ->name('products.show');

Route::get('/product/{id}/edit', [ProductController::class, 'edit'])
    ->name('products.edit');

Route::put('/product/{id}/update', [ProductController::class, 'update'])
    ->name('products.update');

Route::delete('/product/{id}/delete', [ProductController::class, 'destroy'])
    ->name('products.delete');


/*
|--------------------------------------------------------------------------
| Featured
|--------------------------------------------------------------------------
*/

Route::patch(
    '/product/{id}/featured',
    [ProductController::class, 'toggleFeatured']
)->name('products.featured');


/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

Route::patch(
    '/product/{id}/status',
    [ProductController::class, 'toggleStatus']
)->name('products.status');


/*
|--------------------------------------------------------------------------
| Duplicate
|--------------------------------------------------------------------------
*/

Route::post(
    '/product/{id}/duplicate',
    [ProductController::class, 'duplicate']
)->name('products.duplicate');


/*
|--------------------------------------------------------------------------
| Bulk Actions
|--------------------------------------------------------------------------
*/

Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])
    ->name('products.bulk.delete');

Route::post('/products/bulk-status', [ProductController::class, 'bulkStatus'])
    ->name('products.bulk.status');

/*
|--------------------------------------------------------------------------
| Trash
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/trash',
    [ProductController::class, 'trash']
)->name('products.trash');

Route::patch(
    '/products/{id}/restore',
    [ProductController::class, 'restore']
)->name('products.restore');

Route::delete(
    '/products/{id}/force-delete',
    [ProductController::class, 'forceDelete']
)->name('products.force.delete');


/*
|--------------------------------------------------------------------------
| CSV Export
|--------------------------------------------------------------------------
*/

Route::get(
    '/products/export',
    [ProductController::class, 'export']
)->name('products.export');


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

Route::get(
    '/search',
    [ProductController::class, 'search']
)->name('products.search');


/*
|--------------------------------------------------------------------------
| AJAX Search Suggestions
|--------------------------------------------------------------------------
*/

Route::get(
    '/search/suggestions',
    [ProductController::class, 'suggestions']
)->name('products.suggestions');


/*
|--------------------------------------------------------------------------
| Clear Search History
|--------------------------------------------------------------------------
*/

Route::delete(
    '/search/history/clear',
    [ProductController::class, 'clearSearchHistory']
)->name('products.history.clear');
