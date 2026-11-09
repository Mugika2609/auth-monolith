<?php

namespace Modules\Shared\ValueObjects;

use InvalidArgumentException;

final class Denda
{
    public const TARIF_PER_HARI = 1000;

    private function __construct(private readonly int $amount)
    {
    }

    public static function dariKeterlambatan(int $hariTerlambat, int $tarifPerHari = self::TARIF_PER_HARI): self
    {
        if ($tarifPerHari < 0) {
            throw new InvalidArgumentException('Tarif denda tidak boleh negatif.');
        }

        return new self(max(0, $hariTerlambat) * $tarifPerHari);
    }

    public static function nol(): self
    {
        return new self(0);
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function isZero(): bool
    {
        return $this->amount === 0;
    }

    public function format(): string
    {
        return 'Rp'.number_format($this->amount, 0, ',', '.');
    }
}