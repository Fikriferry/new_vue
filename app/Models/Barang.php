<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Tambahkan baris ini di atas
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory; // 2. Tambahkan baris ini di dalam class

    protected $fillable = [
        'nama_barang',
        'kategori_id',
        'stok',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
