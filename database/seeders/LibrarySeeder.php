<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Library\Models\Author;

class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'author' => ['name' => 'Andi Wijaya', 'email' => 'andi@example.com', 'bio' => 'Penulis buku pemrograman web.'],
                'books' => [
                    ['title' => 'Dasar Pemrograman Laravel', 'isbn' => '9786021234501', 'published_year' => 2024, 'price' => 95000, 'stock' => 5],
                    ['title' => 'Membangun REST API', 'isbn' => '9786021234502', 'published_year' => 2025, 'price' => 110000, 'stock' => 3],
                ],
            ],
            [
                'author' => ['name' => 'Sari Lestari', 'email' => 'sari@example.com', 'bio' => 'Penulis buku basis data.'],
                'books' => [
                    ['title' => 'Basis Data untuk Pemula', 'isbn' => '9786021234503', 'published_year' => 2023, 'price' => 85000, 'stock' => 8],
                    ['title' => 'Optimasi Query MySQL', 'isbn' => '9786021234504', 'published_year' => 2022, 'price' => 120000, 'stock' => 2],
                ],
            ],
        ];

        foreach ($data as $row) {
            $author = Author::updateOrCreate(['email' => $row['author']['email']], $row['author']);

            foreach ($row['books'] as $book) {
                $author->books()->updateOrCreate(['isbn' => $book['isbn']], $book);
            }
        }
    }
}