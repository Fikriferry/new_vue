<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barang; // Wajib tambahkan ini agar model Barang terbaca
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $kategoris = ['Elektronik', 'Furniture', 'Alat Tulis', 'Konsumsi'];
        foreach ($kategoris as $kategori) {
            \App\Models\Kategori::create(['nama_kategori' => $kategori]);
        }

        // Tambahkan baris ini untuk membuat 12 data dummy Barang
        Barang::factory(12)->create();
    }
}
