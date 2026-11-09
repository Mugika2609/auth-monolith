<?php

namespace Modules\Catalog\Services;

use Modules\Catalog\Models\Book;
use Modules\Shared\Contracts\BookCatalogContract;
use Modules\Shared\Exceptions\InsufficientStockException;

class BookCatalogService implements BookCatalogContract
{
    public function snapshot(int $bookId): array
    {
        $book = Book::findOrFail($bookId);

        return [
            'id' => $book->id,
            'title' => $book->title,
            'isbn' => $book->isbn,
            'stock' => $book->stock,
        ];
    }

    public function decreaseStock(int $bookId, int $quantity = 1): void
    {
        $book = Book::lockForUpdate()->findOrFail($bookId);

        if ($book->stock < $quantity) {
            throw InsufficientStockException::forBook($book->title, $book->stock);
        }

        $book->decrement('stock', $quantity);
    }

    public function increaseStock(int $bookId, int $quantity = 1): void
    {
        Book::findOrFail($bookId)->increment('stock', $quantity);
    }
}