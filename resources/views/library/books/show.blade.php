<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Buku</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @include('library._alerts')

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-1">
                    <p class="text-xl font-semibold">{{ $book->title }}</p>
                    <p>Penulis: {{ $book->author->name }}</p>
                    <p>ISBN: {{ $book->isbn }}</p>
                    <p>Tahun terbit: {{ $book->published_year }}</p>
                    <p>Harga: Rp {{ number_format($book->price, 2, ',', '.') }}</p>
                    <p>Stok: {{ $book->stock }}</p>
                    <a href="{{ route('books.index') }}" class="inline-block mt-4 text-blue-500 hover:text-blue-700">&larr; Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>