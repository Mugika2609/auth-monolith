<?php

namespace Modules\Shared\Contracts;

interface MemberContract
{
    public function isActive(int $memberId): bool;

    public function loanLimit(int $memberId): int;

    public function loanDays(int $memberId): int;
}