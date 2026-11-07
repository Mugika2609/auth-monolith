<?php

namespace Modules\Library\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class BookNotAvailableException extends Exception
{
    public function __construct(
        protected string $bookTitle,
        protected int $stock = 0
    ) {
        parent::__construct("Buku '{$bookTitle}' tidak tersedia untuk dipinjam (stok habis).");
    }

    public function getBookTitle(): string
    {
        return $this->bookTitle;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function report(): void
    {
        Log::warning('Peminjaman gagal: buku tidak tersedia', [
            'book' => $this->bookTitle,
            'stock' => $this->stock,
        ]);
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'error' => 'book_not_available',
                'details' => [
                    'book' => $this->bookTitle,
                    'stock' => $this->stock,
                ],
            ], 422);
        }

        return back()->with('error', $this->getMessage());
    }
}