<?php

namespace Modules\Shared\Exceptions;

use RuntimeException;

class InsufficientStockException extends RuntimeException
{
    public static function forBook(string $title, int $stock): self
    {
        return new self("Stok buku \"{$title}\" tidak mencukupi (tersisa {$stock}).");
    }
}