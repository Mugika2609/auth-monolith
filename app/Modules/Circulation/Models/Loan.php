<?php

namespace Modules\Circulation\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    public const STATUS_BORROWED = 'borrowed';
    public const STATUS_RETURNED = 'returned';

    protected $fillable = [
        'member_id', 'borrowed_at', 'due_at', 'returned_at', 'fine_amount', 'status',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'datetime',
            'due_at' => 'datetime',
            'returned_at' => 'datetime',
            'fine_amount' => 'integer',
        ];
    }

    // HasMany: satu peminjaman memuat satu atau lebih detail buku.
    // Anggota dan buku hanya dirujuk lewat ID (member_id, book_id) karena
    // berada di context lain.
    public function details(): HasMany
    {
        return $this->hasMany(LoanDetail::class);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNull('returned_at')->where('due_at', '<', now());
    }
}