<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            // Batch 1: Original 5 books
            [
                'judul' => 'Dogs And Wolves',
                'penulis' => 'Jarel Dye',
                'penerbit' => 'Global Press',
                'tahun_terbit' => 2023,
                'harga' => 50000,
                'stok' => 10,
                'gambar' => 'books/book1.png',
                'deskripsi' => "Dogs and Wolves menggambarkan konflik batin manusia antara sisi patuh dan aman (anjing) dengan sisi bebas dan liar (serigala).",
            ],
            [
                'judul' => 'Negeri di Ujung Tanduk',
                'penulis' => 'Tere Liye',
                'penerbit' => 'Gramedia',
                'tahun_terbit' => 2018,
                'harga' => 85000,
                'stok' => 5,
                'gambar' => 'books/book2.png',
                'deskripsi' => "Mengangkat tema politik, kekuasaan, dan konspirasi. Ceritanya mengikuti tokoh utama yang terjebak dalam permainan elit politik penuh intrik.",
            ],
            [
                'judul' => 'Parable',
                'penulis' => 'Brian Khrisna',
                'penerbit' => 'Media Kita',
                'tahun_terbit' => 2018,
                'harga' => 70000,
                'stok' => 12,
                'gambar' => 'books/book3.png',
                'deskripsi' => "Parable menggambarkan perjalanan hidup, cinta, dan pencarian makna diri melalui kisah yang sederhana namun penuh refleksi.",
            ],
            [
                'judul' => 'Seporsi Mie Ayam Sebelum Mati',
                'penulis' => 'Brian Khrisna',
                'penerbit' => 'Loveable',
                'tahun_terbit' => 2021,
                'harga' => 125000,
                'stok' => 1,
                'gambar' => 'books/book4.png',
                'deskripsi' => "Menggambarkan kisah tentang hidup, kehilangan, dan makna sederhana dari kebahagiaan sebelum segalanya berakhir.",
            ],
            [
                'judul' => 'Rumah Lebah',
                'penulis' => 'Ruwi Meita',
                'penerbit' => 'Gagas Media',
                'tahun_terbit' => 2018,
                'harga' => 0,
                'stok' => 4,
                'gambar' => 'books/book5.png',
                'deskripsi' => "Misteri dan rahasia kelam yang tersembunyi di balik sebuah tempat, penuh ketegangan dan teka-teki.",
            ],

            // Batch 2: The Extra 10 Books
            [
                'judul' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'penerbit' => 'Penguin Random House',
                'tahun_terbit' => 2018,
                'harga' => 150000,
                'stok' => 20,
                'gambar' => 'books/book6.png',
                'deskripsi' => "Panduan komprehensif untuk mengubah kebiasaan kecil menjadi hasil yang luar biasa. Buku ini menjelaskan strategi praktis.",
            ],
            [
                'judul' => 'Sapiens: Riwayat Singkat Umat Manusia',
                'penulis' => 'Yuval Noah Harari',
                'penerbit' => 'KPG',
                'tahun_terbit' => 2014,
                'harga' => 120000,
                'stok' => 15,
                'gambar' => 'books/book7.png',
                'deskripsi' => "Sebuah perjalanan sejarah luar biasa sejak manusia pertama kali muncul di bumi hingga era modern.",
            ],
            [
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Lentera Dipantara',
                'tahun_terbit' => 1980,
                'harga' => 95000,
                'stok' => 8,
                'gambar' => 'books/book8.png',
                'deskripsi' => "Mengisahkan perjuangan Minke, seorang pemuda pribumi yang mencoba bangkit dan menemukan identitas bangsanya.",
            ],
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'harga' => 75000,
                'stok' => 14,
                'gambar' => 'books/book9.png',
                'deskripsi' => "Kisah inspiratif anak-anak Belitong yang memperjuangkan pendidikan mereka meski berada di kehidupan tambang.",
            ],
            [
                'judul' => 'The Subtle Art of Not Giving a F*ck',
                'penulis' => 'Mark Manson',
                'penerbit' => 'HarperOne',
                'tahun_terbit' => 2016,
                'harga' => 110000,
                'stok' => 18,
                'gambar' => 'books/book10.png',
                'deskripsi' => "Panduan berlawanan dengan intuisi demi memiliki kehidupan yang lebih baik tanpa memaksa selalu positif.",
            ],
            [
                'judul' => 'Cantik Itu Luka',
                'penulis' => 'Eka Kurniawan',
                'penerbit' => 'Gramedia',
                'tahun_terbit' => 2002,
                'harga' => 135000,
                'stok' => 4,
                'gambar' => 'books/book11.png',
                'deskripsi' => "Perpaduan nyata antara roman epik dan tragedi keluarga, menceritakan kehidupan Dewi Ayu.",
            ],
            [
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'penerbit' => 'Kompas',
                'tahun_terbit' => 2018,
                'harga' => 98000,
                'stok' => 22,
                'gambar' => 'books/book12.png',
                'deskripsi' => "Memperkenalkan konsep filsafat Stoa Kuno untuk bertahan di tengah ketidakpastian zaman.",
            ],
            [
                'judul' => '1984',
                'penulis' => 'George Orwell',
                'penerbit' => 'Secker & Warburg',
                'tahun_terbit' => 1949,
                'harga' => 85000,
                'stok' => 9,
                'gambar' => 'books/book13.png',
                'deskripsi' => "Novel distopia legendaris yang menggambarkan rezim totaliter pemerintah (Big Brother).",
            ],
            [
                'judul' => 'Dunia Sophie',
                'penulis' => 'Jostein Gaarder',
                'penerbit' => 'Mizan',
                'tahun_terbit' => 1991,
                'harga' => 140000,
                'stok' => 7,
                'gambar' => 'books/book14.png',
                'deskripsi' => "Petualangan gadis 14 tahun bernama Sophie Amundsen ke dalam sejarah ringkas filsafat.",
            ],
            [
                'judul' => 'Laut Bercerita',
                'penulis' => 'Leila S. Chudori',
                'penerbit' => 'KPG',
                'tahun_terbit' => 2017,
                'harga' => 105000,
                'stok' => 11,
                'gambar' => 'books/book15.png',
                'deskripsi' => "Kisah dari sudut pandang para mahasiswa buronan penguasa pada akhir 90-an.",
            ],


            







            
        ];

        // Ensure categories exist
        $categories = ['Fiksi', 'Pengembangan Diri', 'Politik', 'Misteri'];
        $createdKategoris = [];
        foreach ($categories as $cat) {
            $createdKategoris[] = \App\Models\KategoriBuku::firstOrCreate(['nama_kategori' => $cat]);
        }

        // Get dummy user
        $peminjamId = \App\Models\User::where('role', 'peminjam')->first()->id ?? 1;

        foreach ($books as $buku) {
            // Kita pakai "firstOrCreate" by judul untuk mencegah duplicate kalau seeding 2x
            $book = Book::firstOrCreate(
                ['judul' => $buku['judul']],
                $buku
            );
            
            // Randomize category if not bound yet
            if ($book->kategoris->count() == 0) {
                $book->kategoris()->attach($createdKategoris[array_rand($createdKategoris)]->id);
            }

            // Generate review jika belum ada
            if (\App\Models\UlasanBuku::where('book_id', $book->id)->count() == 0) {
                \App\Models\UlasanBuku::create([
                    'user_id' => $peminjamId,
                    'book_id' => $book->id,
                    'rating'  => rand(4, 5),
                    'ulasan'  => 'Buku yang sangat bagus, jalan ceritanya menarik dan memberikan insight baru!',
                ]);
            }
        }
    }
}
