<?php

namespace Modules\Membership\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Membership\Models\Member;

class MemberController extends Controller
{
    public function index(): JsonResponse
    {
        // Eager loading: role dan memberships diambil sekaligus
        $members = Member::with(['role', 'memberships'])
            ->active()
            ->orderBy('name')
            ->get();

        return response()->json($members);
    }

    public function destroy(Member $member): JsonResponse
    {
        $member->delete(); // soft delete

        return response()->json(['message' => 'Anggota dihapus (soft delete).']);
    }

    public function restore(int $id): JsonResponse
    {
        $member = Member::onlyTrashed()->findOrFail($id);
        $member->restore();

        return response()->json(['message' => 'Anggota dipulihkan.', 'data' => $member]);
    }
}