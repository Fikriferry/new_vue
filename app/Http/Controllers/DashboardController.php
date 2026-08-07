<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang   = Barang::count();
        $totalKategori = Kategori::count();
        $stokRendah    = Barang::where('stok', '<=', 10)->where('stok', '>', 0)->count();
        $stokHabis     = Barang::where('stok', 0)->count();

        // 5 barang with lowest stock (excluding 0)
        $barangStokRendah = Barang::with('kategori')
            ->where('stok', '>', 0)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();

        // Latest 5 barang added
        $barangTerbaru = Barang::with('kategori')
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalBarang'   => $totalBarang,
                'totalKategori' => $totalKategori,
                'stokRendah'    => $stokRendah,
                'stokHabis'     => $stokHabis,
            ],
            'barangStokRendah' => $barangStokRendah,
            'barangTerbaru'    => $barangTerbaru,
        ]);
    }
}
