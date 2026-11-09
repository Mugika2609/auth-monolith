<?php

namespace Modules\Membership\Services;

use Modules\Membership\Models\Member;
use Modules\Shared\Contracts\MemberContract;

class MemberService implements MemberContract
{
    public function isActive(int $memberId): bool
    {
        return Member::query()->active()->whereKey($memberId)->exists();
    }

    public function loanLimit(int $memberId): int
    {
        return Member::with('role')->findOrFail($memberId)->role->max_loans;
    }

    public function loanDays(int $memberId): int
    {
        return Member::with('role')->findOrFail($memberId)->role->loan_days;
    }
}