<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Membuat nama barang acak (2-3 kata)
            'nama_barang' => fake()->words(rand(2, 3), true),
            
            // Memilih kategori_id secara acak dari tabel Kategori
            'kategori_id' => \App\Models\Kategori::inRandomOrder()->first()->id ?? \App\Models\Kategori::factory()->create()->id,
            
            // Membuat angka stok acak dari 5 sampai 150
            'stok' => fake()->numberBetween(5, 150),
        ];
    }
}