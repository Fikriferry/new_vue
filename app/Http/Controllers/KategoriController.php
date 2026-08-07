<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::latest()->get();
        return \Inertia\Inertia::render('Kategori/Index', [
            'kategoris' => $kategoris
        ]);
    }

    public function create()
    {
        return \Inertia\Inertia::render('Kategori/Create');
    }

    public function store(\App\Http\Requests\StoreKategoriRequest $request)
    {
        Kategori::create($request->validated());
        return redirect()->route('kategori.index');
    }

    public function edit(Kategori $kategori)
    {
        return \Inertia\Inertia::render('Kategori/Edit', [
            'kategori' => $kategori
        ]);
    }

    public function update(\App\Http\Requests\StoreKategoriRequest $request, Kategori $kategori)
    {
        $kategori->update($request->validated());
        return redirect()->route('kategori.index');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index');
    }
}
