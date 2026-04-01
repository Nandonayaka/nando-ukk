<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $books = Book::when($search, function($query, $search) {
            return $query->where('judul', 'like', "%{$search}%")
                         ->orWhere('penulis', 'like', "%{$search}%")
                         ->orWhere('penerbit', 'like', "%{$search}%");
        })->get();
        return view('books.index', compact('books'));
    }

    public function katalog(Request $request)
    {
        $search = $request->query('search');
        
        // Banner tetap (tidak terpengaruh search)
        $featuredBooks = Book::latest()->take(5)->get();

        // Grid terfilter oleh search
        $books = Book::when($search, function($query, $search) {
            return $query->where('judul', 'like', "%{$search}%")
                         ->orWhere('penulis', 'like', "%{$search}%")
                         ->orWhere('penerbit', 'like', "%{$search}%");
        })->get();

        return view('pelanggan.katalog', compact('books', 'featuredBooks'));
    }

    public function history()
    {
        if (auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas') {
            $peminjamans = Peminjaman::with(['book', 'user'])->latest()->get();
        } else {
            $peminjamans = Peminjaman::where('user_id', auth()->id())->with('book')->latest()->get();
        }
        return view('pelanggan.history', compact('peminjamans'));
    }

    public function pinjam(Request $request, Book $book)
    {
        if ($book->stok <= 0) {
            return back()->withErrors(['stok' => 'Maaf, buku ini sedang tidak tersedia (habis).']);
        }

        // Check if already borrowed
        $alreadyBorrowed = Peminjaman::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->where('status_peminjaman', 'Pinjam')
            ->exists();

        if ($alreadyBorrowed) {
            return back()->withErrors(['peminjaman' => 'Anda sedang meminjam buku ini.']);
        }

        $book->decrement('stok');

        Peminjaman::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'tanggal_peminjaman' => now(),
            'status_peminjaman' => 'Pinjam'
        ]);

        $isAdmin = (auth()->user()->role === 'administrator' || auth()->user()->role === 'petugas');
        $route = $isAdmin ? 'books.index' : 'katalog.index';
        return redirect()->route($route)->with('success', 'Berhasil meminjam buku: ' . $book->judul);
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman === 'Kembali') {
            return back()->withErrors(['status' => 'Buku ini sudah dikembalikan.']);
        }

        $peminjaman->update([
            'tanggal_pengembalian' => now(),
            'status_peminjaman' => 'Kembali'
        ]);

        $peminjaman->book()->increment('stok');

        return back()->with('success', 'Buku berhasil dikembalikan.');
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
            'penerbit' => 'nullable',
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
            'penerbit' => 'nullable',
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
