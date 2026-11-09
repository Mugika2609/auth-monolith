<?php

namespace Modules\Circulation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Circulation\Models\Loan;

class LoanController extends Controller
{
    public function index(): JsonResponse
    {
        // Eager loading: detail peminjaman diambil sekaligus
        $loans = Loan::with('details')
            ->latest('borrowed_at')
            ->paginate(10);

        return response()->json($loans);
    }

    public function destroy(Loan $loan): JsonResponse
    {
        $loan->delete(); // soft delete

        return response()->json(['message' => 'Peminjaman dihapus (soft delete).']);
    }

    public function restore(int $id): JsonResponse
    {
        $loan = Loan::onlyTrashed()->findOrFail($id);
        $loan->restore();

        return response()->json(['message' => 'Peminjaman dipulihkan.', 'data' => $loan]);
    }
}