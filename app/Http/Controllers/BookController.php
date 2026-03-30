<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    public function katalog()
    {
        $books = Book::all();
        return view('pelanggan.katalog', compact('books'));
    }

    public function history()
    {
        $transactions = \App\Models\Transaction::where('user_id', auth()->id())->with('book')->latest()->get();
        return view('pelanggan.history', compact('transactions'));
    }

    public function beli(Request $request, Book $book)
    {
        if ($book->stok <= 0) {
            return back()->withErrors(['stok' => 'Maaf, buku ini sedang tidak tersedia (habis).']);
        }

        $book->decrement('stok');

        \App\Models\Transaction::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'status' => 'Berhasil'
        ]);

        $route = auth()->user()->role === 'admin' ? 'books.index' : 'katalog.index';
        return redirect()->route($route)->with('success', 'Berhasil melakukan interaksi/pembelian buku: ' . $book->judul);
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric',
            'stok' => 'nullable|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $data['gambar'] = $filename;
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric',
            'stok' => 'nullable|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $data['gambar'] = $filename;
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
