<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\KategoriBuku;
use App\Models\InboxMessage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $stockStatus = $request->query('stock_status');

        $books = Book::with('kategoris')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('penerbit', 'like', "%{$search}%")
                      ->orWhere('tahun_terbit', 'like', "%{$search}%");
                });
            })
            ->when($category, function($query, $categoryId) {
                return $query->whereHas('kategoris', function($q) use ($categoryId) {
                    $q->where('kategoribukus.id', $categoryId);
                });
            })
            ->when($stockStatus, function($query, $status) {
                if ($status === 'menipis') return $query->where('stok', '>', 0)->where('stok', '<=', 10);
                if ($status === 'habis') return $query->where('stok', '<=', 0);
                if ($status === 'tersedia') return $query->where('stok', '>', 10);
            })
            ->latest()
            ->paginate(15)->withQueryString();

        $kategoris = KategoriBuku::all();

        return view('books.index', compact('books', 'kategoris'));
    }

    public function katalog(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        
        // Terbaru paling atas
        $featuredBooks = collect();
        $popularBooks = collect();
        
        if (!$search && !$category) {
            $featuredBooks = Book::latest()->take(5)->get();
            $popularBooks = Book::with('kategoris')
                ->withAvg('ulasanBukus', 'rating')
                ->withCount('ulasanBukus')
                ->orderByDesc('ulasan_bukus_avg_rating')
                ->orderByDesc('ulasan_bukus_count')
                ->take(10)
                ->get();
        }

        // Grid terfilter oleh search dan dipaginate (Eager load kategoris)
        $books = Book::with('kategoris')
            ->withAvg('ulasanBukus', 'rating')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('penerbit', 'like', "%{$search}%");
                });
            })
            ->when($category, function($query, $categoryId) {
                return $query->whereHas('kategoris', function($q) use ($categoryId) {
                    $q->where('kategoribukus.id', $categoryId);
                });
            })
            ->latest()
            ->paginate(10)->withQueryString();

        $kategoris = KategoriBuku::all();

        return view('pelanggan.katalog', compact('books', 'featuredBooks', 'kategoris', 'popularBooks'));
    }

    public function history(Request $request)
    {
        $query = Peminjaman::with(['book', 'user', 'book.kategoris']);

        if (auth()->user()->role !== 'administrator' && auth()->user()->role !== 'petugas') {
            $query->where('user_id', auth()->id());
        }

        // Search Filter (Judul Buku or Nama Peminjam)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('book', function($b) use ($search) {
                    $b->where('judul', 'like', "%$search%");
                })->orWhereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                });
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->whereHas('book', function($b) use ($request) {
                $b->whereHas('kategoris', function($k) use ($request) {
                    $k->where('kategoribukus.id', $request->category);
                });
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status_peminjaman', $request->status);
        }

        $peminjamans = $query->latest()->paginate(15)->withQueryString();
        $kategoris = KategoriBuku::all();

        return view('pelanggan.history', compact('peminjamans', 'kategoris'));
    }

    public function pinjam(Request $request, Book $book)
    {
        $maxDate = now()->addDays(12);
        
        $request->validate([
            'tanggal_jatuh_tempo' => 'required|date|after:now|before_or_equal:' . $maxDate,
        ], [
            'tanggal_jatuh_tempo.required' => 'Mohon tentukan waktu pengembalian buku.',
            'tanggal_jatuh_tempo.date' => 'Format waktu tidak valid.',
            'tanggal_jatuh_tempo.after' => 'Waktu pengembalian harus lebih lambat dari waktu sekarang.',
            'tanggal_jatuh_tempo.before_or_equal' => 'Maksimal durasi peminjaman adalah 12 hari.',
        ]);

        if ($book->stok <= 0) {
            return back()->withErrors(['stok' => 'Maaf, buku ini sedang tidak tersedia (habis).']);
        }

        // Limit user to 4 active books (Pinjam or Menunggu)
        $activeLoansCount = Peminjaman::where('user_id', auth()->id())
            ->whereIn('status_peminjaman', ['Pinjam', 'Menunggu'])
            ->count();
            
        if ($activeLoansCount >= 4) {
            return back()->withErrors(['peminjaman' => 'Anda telah mencapai batas maksimal peminjaman (4 buku). Mohon kembalikan buku sebelumnya.']);
        }

        // Check if already borrowed or pending
        $alreadyBorrowed = Peminjaman::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereIn('status_peminjaman', ['Pinjam', 'Menunggu'])
            ->exists();

        if ($alreadyBorrowed) {
            return back()->withErrors(['peminjaman' => 'Anda sedang meminjam atau menunggu konfirmasi untuk buku ini.']);
        }

        $book->decrement('stok');

        Peminjaman::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'tanggal_peminjaman' => now(),
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status_peminjaman' => 'Menunggu' // Changed to await admin confirmation
        ]);

        // Send inbox notification to User
        InboxMessage::kirim(
            auth()->id(),
            'pinjam',
            'Menunggu Konfirmasi',
            'Permintaan peminjaman buku "' . $book->judul . '" sedang menunggu konfirmasi admin.',
            'fas fa-clock',
            'yellow'
        );

        // Send inbox notification to all Admins
        $admins = \App\Models\User::where('role', 'administrator')->get();
        foreach ($admins as $admin) {
            InboxMessage::kirim(
                $admin->id,
                'pinjam',
                'Permintaan Peminjaman Baru',
                auth()->user()->name . ' ingin meminjam buku "' . $book->judul . '". Segera cek laporan untuk konfirmasi.',
                'fas fa-bell',
                'blue'
            );
        }

        return redirect()->route('katalog.index')->with('success', 'Permintaan peminjaman diajukan. Menunggu konfirmasi admin.');
    }

    public function konfirmasi(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman === 'Menunggu') {
            $peminjaman->update([
                'status_peminjaman' => 'Pinjam',
                'tanggal_peminjaman' => now() // Reset timer format to when it starts
            ]);

            // Add EXP upon confirmation instead of immediately
            $peminjaman->user->increment('exp', 25);

            InboxMessage::kirim(
                $peminjaman->user_id,
                'pinjam',
                'Peminjaman Dikonfirmasi',
                'Peminjaman buku "' . $peminjaman->book->judul . '" telah dikonfirmasi. Jangan lupa kembalikan sebelum jatuh tempo ya! (+25 EXP)',
                'fas fa-check-circle',
                'green'
            );

            return back()->with('success', 'Peminjaman berhasil dikonfirmasi.');
        }
        return back()->with('error', 'Status peminjaman tidak valid untuk dikonfirmasi.');
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman === 'Kembali') {
            return back()->withErrors(['status' => 'Buku ini sudah dikembalikan.']);
        }

        $now = now();
        $denda = 0;
        
        // Check if late (more than the specified due date)
        if ($peminjaman->tanggal_jatuh_tempo && $now->gt($peminjaman->tanggal_jatuh_tempo)) {
            // Fine logic: Rp 12.000 just for being late (even 1 second)
            $denda = 12000;
            
            // If they are more than one day late, we add per day.
            $diffInDays = $now->diffInDays($peminjaman->tanggal_jatuh_tempo);
            if ($diffInDays > 0) {
                $denda += ($diffInDays * 12000); // 12rb per hari
            }
        }

        $peminjaman->update([
            'tanggal_pengembalian' => $now,
            'status_peminjaman' => 'Kembali',
            'denda' => $denda,
            'status_denda' => $denda > 0 ? 'Belum Lunas' : null
        ]);

        $peminjaman->book()->increment('stok');

        $message = 'Buku berhasil dikembalikan.';
        if ($denda > 0) {
            $message .= ' Anda terlambat dan dikenakan denda sebesar Rp ' . number_format($denda, 0, ',', '.') . '. Mohon segera lakukan pembayaran.';

            // Kirim notifikasi denda
            InboxMessage::kirim(
                $peminjaman->user_id,
                'denda',
                'Denda Keterlambatan',
                'Anda dikenakan denda sebesar Rp ' . number_format($denda, 0, ',', '.') . ' karena terlambat mengembalikan buku "' . $peminjaman->book->judul . '". Segera lakukan pembayaran.',
                'fas fa-exclamation-triangle',
                'red'
            );
        }

        return back()->with('success', $message);
    }

    public function bayarDenda(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_denda !== 'Belum Lunas') {
            return back()->withErrors(['denda' => 'Tidak ada denda yang perlu dibayar.']);
        }

        $peminjaman->update([
            'status_denda' => 'Lunas'
        ]);

        // Kirim notifikasi pembayaran denda berhasil
        InboxMessage::kirim(
            $peminjaman->user_id,
            'bayar_denda',
            'Pembayaran Denda Berhasil',
            'Pembayaran denda sebesar Rp ' . number_format($peminjaman->denda, 0, ',', '.') . ' telah berhasil. Terima kasih telah menyelesaikan kewajiban Anda!',
            'fas fa-check-circle',
            'green'
        );

        return back()->with('success', 'Denda berhasil dibayar. Terima kasih!');
    }

    public function create()
    {
        $kategoris = KategoriBuku::all();
        return view('books.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'nullable',
            'tahun_terbit' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'stok' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categories' => 'nullable|array'
        ]);

        $data = $request->except('categories');

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $data['gambar'] = $filename;
        }

        $book = Book::create($data);

        if ($request->has('categories')) {
            $book->kategoris()->sync($request->categories);
        }

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $book->load(['ulasanBukus.user', 'kategoris']);
        $userUlasan = auth()->check() ? $book->ulasanBukus->where('user_id', auth()->id())->first() : null;
        return view('books.show', compact('book', 'userUlasan'));
    }

    public function storeUlasan(Request $request, Book $book)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|max:1000',
        ]);

        $existing = \App\Models\UlasanBuku::where('user_id', auth()->id())
                                          ->where('book_id', $book->id)
                                          ->first();
        if ($existing) {
            $existing->update([
                'rating' => $request->rating,
                'ulasan' => $request->ulasan
            ]);
            auth()->user()->increment('exp', 2);
            $msg = 'Ulasan Anda berhasil diupdate (+2 EXP).';
        } else {
            \App\Models\UlasanBuku::create([
                'user_id' => auth()->id(),
                'book_id' => $book->id,
                'rating' => $request->rating,
                'ulasan' => $request->ulasan
            ]);
            auth()->user()->increment('exp', 25);
            $msg = 'Ulasan Anda telah disimpan (+25 EXP).';
        }

        return back()->with('success', $msg);
    }

    public function deleteUlasan(\App\Models\UlasanBuku $ulasan)
    {
        // Admin, Petugas, and the reviewer can delete
        if (auth()->user()->role === 'peminjam' && auth()->id() !== $ulasan->user_id) {
            abort(403);
        }

        $ulasan->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }

    public function edit(Book $book)
    {
        $kategoris = KategoriBuku::all();
        return view('books.edit', compact('book', 'kategoris'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'nullable',
            'tahun_terbit' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'stok' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categories' => 'nullable|array'
        ]);

        $data = $request->except('categories');

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $data['gambar'] = $filename;
        }

        $book->update($data);

        if ($request->has('categories')) {
            $book->kategoris()->sync($request->categories);
        } else {
            $book->kategoris()->detach();
        }

        return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }

    public function tolak(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman !== 'Menunggu') {
            return back()->withErrors(['peminjaman' => 'Hanya permintaan menunggu yang dapat ditolak.']);
        }

        $peminjaman->book->increment('stok');
        $peminjaman->update(['status_peminjaman' => 'Ditolak']);

        InboxMessage::kirim(
            $peminjaman->user_id,
            'pinjam',
            'Peminjaman Ditolak',
            'Maaf, permintaan pinjam "' . $peminjaman->book->judul . '" ditolak oleh admin.',
            'fas fa-times-circle',
            'red'
        );

        return back()->with('success', 'Permintaan peminjaman ditolak.');
    }

    public function batalkan(Peminjaman $peminjaman)
    {
        if (auth()->id() !== $peminjaman->user_id || $peminjaman->status_peminjaman !== 'Menunggu') {
            return back()->withErrors(['peminjaman' => 'Hanya permintaan menunggu yang dapat dibatalkan.']);
        }

        $peminjaman->book->increment('stok');
        $peminjaman->update(['status_peminjaman' => 'Dibatalkan']);

        return back()->with('success', 'Permintaan peminjaman berhasil dibatalkan.');
    }

    public function exportPdf(Peminjaman $peminjaman)
    {
        $peminjaman->load(['book', 'user']);
        $pdf = Pdf::loadView('pelanggan.pdf_single', compact('peminjaman'));
        return $pdf->download('Bukti_Pinjam_' . $peminjaman->id . '.pdf');
    }

    public function exportAllPdf(Request $request)
    {
        $query = Peminjaman::with(['book', 'user']);

        if (auth()->user()->role !== 'administrator' && auth()->user()->role !== 'petugas') {
            $query->where('user_id', auth()->id());
        }

        // Apply same filters as history index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('book', function($b) use ($search) {
                    $b->where('judul', 'like', "%$search%");
                })->orWhereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                });
            });
        }
        if ($request->filled('category')) {
            $query->whereHas('book', function($b) use ($request) {
                $b->whereHas('kategoris', function($k) use ($request) {
                    $k->where('kategoribukus.id', $request->category);
                });
            });
        }
        if ($request->filled('status')) {
            $query->where('status_peminjaman', $request->status);
        }

        $peminjamans = $query->latest()->get();
        $pdf = Pdf::loadView('pelanggan.pdf_all', compact('peminjamans'));
        return $pdf->download('Laporan_Peminjaman.pdf');
    }
}
