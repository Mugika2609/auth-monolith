<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\Author;
use Modules\Catalog\Models\Book;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Publisher;
use Modules\Membership\Models\Member;
use Modules\Membership\Models\Membership;
use Modules\Membership\Models\Role;

class CirculationDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Catalog: penerbit
        $publishers = collect(['Gramedia', 'Erlangga', 'Informatika'])
            ->map(fn ($name) => Publisher::firstOrCreate(['name' => $name]))
            ->values();

        // Catalog: kategori induk dan sub-kategori (self-referencing)
        $teknologi = Category::firstOrCreate(['name' => 'Teknologi']);
        $pemrograman = Category::firstOrCreate(['name' => 'Pemrograman'], ['parent_id' => $teknologi->id]);
        $basisData = Category::firstOrCreate(['name' => 'Basis Data'], ['parent_id' => $teknologi->id]);
        $sastra = Category::firstOrCreate(['name' => 'Sastra']);
        $categories = collect([$teknologi, $pemrograman, $basisData, $sastra]);

        // Catalog: penulis
        $authors = collect([
            ['Rina Wijaya', 'rina.wijaya@example.com'],
            ['Agus Hartono', 'agus.hartono@example.com'],
            ['Maya Kusuma', 'maya.kusuma@example.com'],
        ])->map(fn ($a) => Author::firstOrCreate(['email' => $a[1]], ['name' => $a[0]]))->values();

        // Catalog: buku (satu buku berstok 0 untuk uji rollback)
        $titles = [
            ['Pemrograman PHP Modern', 5],
            ['Laravel untuk Pemula', 5],
            ['Basis Data Relasional', 4],
            ['Struktur Data dan Algoritma', 3],
            ['Jaringan Komputer Dasar', 6],
            ['Keamanan Aplikasi Web', 2],
            ['Rekayasa Perangkat Lunak', 5],
            ['Sistem Operasi', 4],
            ['Kecerdasan Buatan', 3],
            ['Desain Antarmuka Pengguna', 5],
            ['Buku Stok Habis (Uji Rollback)', 0],
            ['Kumpulan Cerita Nusantara', 7],
        ];

        foreach ($titles as $i => [$title, $stock]) {
            $book = Book::firstOrCreate(
                ['isbn' => $this->isbn13($i + 1)],
                [
                    'title' => $title,
                    'published_year' => 2015 + ($i % 10),
                    'price' => 50000 + ($i * 5000),
                    'stock' => $stock,
                    'author_id' => $authors[$i % $authors->count()]->id,
                    'publisher_id' => $publishers[$i % $publishers->count()]->id,
                ],
            );

            $book->categories()->syncWithoutDetaching(
                $categories->random(2)->pluck('id')->all()
            );
        }

        // Membership: role dan anggota
        $mahasiswa = Role::firstOrCreate(['name' => 'Mahasiswa'], ['max_loans' => 3, 'loan_days' => 7]);
        $dosen = Role::firstOrCreate(['name' => 'Dosen'], ['max_loans' => 5, 'loan_days' => 14]);
        $umum = Role::firstOrCreate(['name' => 'Umum'], ['max_loans' => 2, 'loan_days' => 5]);

        $members = [
            ['Budi Santoso', 'budi@example.com', $mahasiswa, Member::STATUS_ACTIVE],
            ['Siti Aminah', 'siti@example.com', $mahasiswa, Member::STATUS_ACTIVE],
            ['Rahmat Hidayat', 'rahmat@example.com', $dosen, Member::STATUS_ACTIVE],
            ['Dewi Lestari', 'dewi@example.com', $umum, Member::STATUS_ACTIVE],
            ['Andi Pratama', 'andi@example.com', $mahasiswa, Member::STATUS_SUSPENDED],
        ];

        foreach ($members as [$name, $email, $role, $status]) {
            $member = Member::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'role_id' => $role->id, 'status' => $status],
            );

            Membership::firstOrCreate(
                ['member_id' => $member->id],
                [
                    'started_at' => now()->subMonths(2)->toDateString(),
                    'expires_at' => now()->addMonths(10)->toDateString(),
                ],
            );
        }
    }

    // Membuat ISBN-13 yang valid (digit pemeriksa dihitung otomatis)
    private function isbn13(int $seed): string
    {
        $base = '978'.str_pad((string) (100000000 + $seed), 9, '0', STR_PAD_LEFT);
        $sum = 0;
        foreach (str_split($base) as $i => $digit) {
            $sum += (int) $digit * ($i % 2 === 0 ? 1 : 3);
        }

        return $base.((10 - $sum % 10) % 10);
    }
}