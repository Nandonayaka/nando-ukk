<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KoleksiPribadi;
use App\Models\Book;
use App\Models\KategoriBuku;

class KoleksiPribadiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $koleksi = Book::whereHas('koleksipribadis', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->with(['kategoris'])
            ->withAvg('ulasanBukus', 'rating')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%");
                });
            })
            ->when($category, function($query, $categoryId) {
                return $query->whereHas('kategoris', function($q) use ($categoryId) {
                    $q->where('kategoribukus.id', $categoryId);
                });
            })
            ->get();

        $kategoris = KategoriBuku::all();
            
        return view('pelanggan.favorit', compact('koleksi', 'kategoris'));
    }

    public function toggle(Request $request, Book $book)
    {
        $existing = KoleksiPribadi::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Buku dihapus dari favorit.');
        }

        KoleksiPribadi::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id
        ]);

        return back()->with('success', 'Buku ditambahkan ke favorit.');
    }
}
