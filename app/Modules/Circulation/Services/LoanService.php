<?php

namespace Modules\Circulation\Services;

use Illuminate\Support\Facades\DB;
use Modules\Circulation\Events\BookBorrowed;
use Modules\Circulation\Models\Loan;
use Modules\Shared\Contracts\BookCatalogContract;
use Modules\Shared\Contracts\MemberContract;
use Modules\Shared\Exceptions\MemberNotEligibleException;
use Modules\Shared\ValueObjects\Denda;

class LoanService
{
    public function __construct(
        private readonly BookCatalogContract $books,
        private readonly MemberContract $members,
    ) {
    }

    /**
     * Meminjam satu atau lebih buku dalam satu transaction.
     * Jika salah satu langkah gagal, seluruh perubahan dibatalkan (rollback).
     *
     * @param  int[]  $bookIds
     */
    public function borrow(int $memberId, array $bookIds): Loan
    {
        if (! $this->members->isActive($memberId)) {
            throw MemberNotEligibleException::notActive($memberId);
        }

        $limit = $this->members->loanLimit($memberId);
        if (count($bookIds) > $limit) {
            throw MemberNotEligibleException::limitExceeded($memberId, $limit);
        }

        $loan = DB::transaction(function () use ($memberId, $bookIds) {
            $loan = Loan::create([
                'member_id' => $memberId,
                'borrowed_at' => now(),
                'due_at' => now()->addDays($this->members->loanDays($memberId)),
                'status' => Loan::STATUS_BORROWED,
            ]);

            foreach ($bookIds as $bookId) {
                $snapshot = $this->books->snapshot($bookId);
                $this->books->decreaseStock($bookId);

                $loan->details()->create([
                    'book_id' => $snapshot['id'],
                    'book_title' => $snapshot['title'],
                    'book_isbn' => $snapshot['isbn'],
                ]);
            }

            return $loan;
        });

        // Event hanya ditembakkan setelah transaction berhasil di-commit
        BookBorrowed::dispatch($loan, $bookIds);

        return $loan->load('details');
    }

    /**
     * Mengembalikan peminjaman: stok dikembalikan dan denda dihitung
     * dengan value object Denda.
     */
    public function returnLoan(int $loanId): Loan
    {
        return DB::transaction(function () use ($loanId) {
            $loan = Loan::with('details')->lockForUpdate()->findOrFail($loanId);

            if ($loan->returned_at !== null) {
                return $loan;
            }

            $returnedAt = now();
            $lateDays = (int) max(0, $loan->due_at->copy()->startOfDay()
                ->diffInDays($returnedAt->copy()->startOfDay(), false));
            $denda = Denda::dariKeterlambatan($lateDays);

            foreach ($loan->details as $detail) {
                $this->books->increaseStock($detail->book_id);
            }

            $loan->update([
                'returned_at' => $returnedAt,
                'fine_amount' => $denda->amount(),
                'status' => Loan::STATUS_RETURNED,
            ]);

            return $loan->fresh('details');
        });
    }
}