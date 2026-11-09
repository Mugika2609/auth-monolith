<?php

namespace Modules\Shared\ValueObjects;

use Modules\Shared\Exceptions\InvalidIsbnException;
use Stringable;

final class ISBN implements Stringable
{
    private function __construct(private readonly string $value)
    {
    }

    public static function from(string $raw): self
    {
        $clean = strtoupper(str_replace(['-', ' '], '', $raw));

        if (! self::isValid($clean)) {
            throw InvalidIsbnException::for($raw);
        }

        return new self($clean);
    }

    public static function isValid(string $clean): bool
    {
        if (preg_match('/^\d{9}[\dX]$/', $clean)) {
            return self::checkIsbn10($clean);
        }

        if (preg_match('/^\d{13}$/', $clean)) {
            return self::checkIsbn13($clean);
        }

        return false;
    }

    private static function checkIsbn10(string $isbn): bool
    {
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $digit = $isbn[$i] === 'X' ? 10 : (int) $isbn[$i];
            $sum += $digit * (10 - $i);
        }

        return $sum % 11 === 0;
    }

    private static function checkIsbn13(string $isbn): bool
    {
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $isbn[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        return $sum % 10 === 0;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}