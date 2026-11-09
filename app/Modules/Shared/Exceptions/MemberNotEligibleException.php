<?php

namespace Modules\Shared\Exceptions;

use RuntimeException;

class MemberNotEligibleException extends RuntimeException
{
    public static function notActive(int $memberId): self
    {
        return new self("Anggota #{$memberId} tidak berstatus aktif dan tidak dapat meminjam.");
    }

    public static function limitExceeded(int $memberId, int $limit): self
    {
        return new self("Anggota #{$memberId} melebihi batas pinjam ({$limit} buku).");
    }
}