<?php

namespace App\Http\Controllers;

use App\Models\KategoriBuku;
use Illuminate\Http\Request;

class KategoriBukuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categories = KategoriBuku::when($search, function($query, $search) {
            return $query->where('nama_kategori', 'like', "%{$search}%");
        })->latest()->paginate(10)->withQueryString();
        
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|max:12|unique:kategoribukus,nama_kategori']);
        KategoriBuku::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(KategoriBuku $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, KategoriBuku $category)
    {
        $request->validate(['nama_kategori' => 'required|max:12|unique:kategoribukus,nama_kategori,' . $category->id]);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriBuku $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
