<?php

use Illuminate\Support\Facades\Route;
use Modules\Catalog\Controllers\BookController;

Route::middleware('web')->prefix('catalog')->name('catalog.')->group(function () {
    Route::get('books', [BookController::class, 'index'])->name('books.index');
    Route::get('books/{book}', [BookController::class, 'show'])->name('books.show');
});

// Hapus dan restore hanya untuk admin
Route::middleware(['web', 'auth', 'role:admin'])->prefix('admin/catalog')->name('admin.catalog.')->group(function () {
    Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::patch('books/{id}/restore', [BookController::class, 'restore'])->name('books.restore');
});
