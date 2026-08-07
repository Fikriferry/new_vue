<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Http\Requests\StoreBarangRequest;
use Inertia\Inertia;

class BarangController extends Controller
{
    public function index()
    {
        $items = Barang::with('kategori')->latest()->get();
        return Inertia::render('Barang/Index', [
            'items' => $items
        ]);
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return Inertia::render('Barang/Create', [
            'kategoris' => $kategoris
        ]);
    }

    public function store(StoreBarangRequest $request)
    {
        Barang::create($request->validated());

        return redirect()->route('barang.index');
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        return Inertia::render('Barang/Edit', [
            'barang' => $barang,
            'kategoris' => $kategoris
        ]);
    }

    public function update(StoreBarangRequest $request, Barang $barang)
    {
        $barang->update($request->validated());

        return redirect()->route('barang.index');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index');
    }
}