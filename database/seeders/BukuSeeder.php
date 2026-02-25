<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Buku;
use App\Models\Kategori;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID kategori
        $novelId = Kategori::where('nama_kategori', 'Novel')->first()->idkategori;
        $biografiId = Kategori::where('nama_kategori', 'Biografi')->first()->idkategori;

        $bukus = [
            [
                'kode' => 'NV-01',
                'judul' => 'Home Sweet Loan',
                'pengarang' => 'Almira Bastari',
                'idkategori' => $novelId
            ],
            [
                'kode' => 'BO-01',
                'judul' => 'Mohammad Hatta, Untuk Negeriku',
                'pengarang' => 'Taufik Abdullah',
                'idkategori' => $biografiId
            ],
            [
                'kode' => 'NV-02',
                'judul' => 'Keajaiban Toko Kelontong Namiya',
                'pengarang' => 'Keigo Higashino',
                'idkategori' => $novelId
            ],
        ];

        foreach ($bukus as $buku) {
            Buku::create($buku);
        }
    }
}
