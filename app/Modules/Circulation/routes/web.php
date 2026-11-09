<?php

use Illuminate\Support\Facades\Route;
use Modules\Circulation\Controllers\LoanController;

Route::middleware('web')->prefix('circulation')->name('circulation.')->group(function () {
    Route::get('loans', [LoanController::class, 'index'])->name('loans.index');
});

// Hapus dan restore hanya untuk admin
Route::middleware(['web', 'auth', 'role:admin'])->prefix('admin/circulation')->name('admin.circulation.')->group(function () {
    Route::delete('loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');
    Route::patch('loans/{id}/restore', [LoanController::class, 'restore'])->name('loans.restore');
});
