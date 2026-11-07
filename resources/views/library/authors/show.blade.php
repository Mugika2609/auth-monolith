<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Penulis</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @include('library._alerts')

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <p class="text-xl font-semibold">{{ $author->name }}</p>
                    <p class="text-gray-600">{{ $author->email }}</p>
                    <p class="mt-3">{{ $author->bio ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold mb-3">Buku ({{ $author->books->count() }})</h3>
                    <ul class="list-disc list-inside text-gray-700">
                        @forelse ($author->books as $book)
                            <li>{{ $book->title }} — ISBN {{ $book->isbn }}, stok {{ $book->stock }}</li>
                        @empty
                            <li class="list-none text-gray-500">Belum ada buku.</li>
                        @endforelse
                    </ul>
                    <a href="{{ route('authors.index') }}" class="inline-block mt-4 text-blue-500 hover:text-blue-700">&larr; Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>