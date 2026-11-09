<?php

use Illuminate\Support\Facades\Route;
use Modules\Membership\Controllers\MemberController;

Route::middleware('web')->prefix('membership')->name('membership.')->group(function () {
    Route::get('members', [MemberController::class, 'index'])->name('members.index');
});

// Hapus dan restore hanya untuk admin
Route::middleware(['web', 'auth', 'role:admin'])->prefix('admin/membership')->name('admin.membership.')->group(function () {
    Route::delete('members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
    Route::patch('members/{id}/restore', [MemberController::class, 'restore'])->name('members.restore');
});
