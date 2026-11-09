<?php

namespace Modules\Catalog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Catalog\Models\Book;

class BookController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Eager loading: author, publisher, dan categories diambil sekaligus
        $books = Book::with(['author', 'publisher', 'categories'])
            ->available()
            ->search($request->query('search'))
            ->inCategory($request->integer('category_id') ?: null)
            ->orderBy('title')
            ->paginate(10);

        return response()->json($books);
    }

    public function show(Book $book): JsonResponse
    {
        return response()->json($book->load(['author', 'publisher', 'categories']));
    }

    public function destroy(Book $book): JsonResponse
    {
        $book->delete(); // soft delete

        return response()->json(['message' => 'Buku dihapus (soft delete).']);
    }

    public function restore(int $id): JsonResponse
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        $book->restore();

        return response()->json(['message' => 'Buku dipulihkan.', 'data' => $book]);
    }
}