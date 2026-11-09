<?php

namespace Modules\Shared\Contracts;

interface BookCatalogContract
{
    /**
     * Data ringkas buku untuk disalin (snapshot) ke detail peminjaman.
     *
     * @return array{id: int, title: string, isbn: string, stock: int}
     */
    public function snapshot(int $bookId): array;

    public function decreaseStock(int $bookId, int $quantity = 1): void;

    public function increaseStock(int $bookId, int $quantity = 1): void;
}