<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        //Akun Administrator
        User::create([
            'name' => 'Irham Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'administrator',
            'nama_lengkap' => 'Irham Administrator',
            'alamat' => 'Jakarta, Indonesia',
        ]);

        //Akun Petugas
        User::create([
            'name' => 'Nando Petugas',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'petugas',
            'nama_lengkap' => 'Nando Petugas Perpustakaan',
            'alamat' => 'Bandung, Indonesia',
        ]);

        //Akun Peminjam
        User::create([
            'name' => 'Budi Peminjam',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'peminjam',
            'nama_lengkap' => 'Budi Santoso',
            'alamat' => 'Surabaya, Indonesia',
        ]);
            
        //Buku-buku Dummy
        $books = [
            [
                'judul' => 'Dogs And Wolves',
                'penulis' => 'Jarel Dye',
                'penerbit' => 'Global Press',
                'tahun_terbit' => 2023,
                'harga' => 50000,
                'stok' => 10,
                'gambar' => 'book1.png',
                'deskripsi' => "Dogs and Wolves menggambarkan konflik batin manusia antara sisi patuh dan aman (anjing) dengan sisi bebas dan liar (serigala). Ceritanya berfokus pada pilihan sulit antara mengikuti aturan atau menjadi diri sendiri, dengan konsekuensi yang tidak pernah benar-benar mudah.",
            ],
            [
                'judul' => 'Negeri di Ujung Tanduk',
                'penulis' => 'Tere Liye',
                'penerbit' => 'Gramedia',
                'tahun_terbit' => 2018,
                'harga' => 85000,
                'stok' => 5,
                'gambar' => 'book2.png',
                'deskripsi' => "Mengangkat tema politik, kekuasaan, dan konspirasi. Ceritanya mengikuti tokoh utama yang terjebak dalam permainan elit politik penuh intrik, manipulasi, dan pengkhianatan. Ia harus menghadapi berbagai risiko untuk mengungkap kebenaran di tengah sistem yang sudah rusak.",
            ],
            [
                'judul' => 'Parable',
                'penulis' => 'Brian Khrisna',
                'penerbit' => 'Media Kita',
                'tahun_terbit' => 2018,
                'harga' => 70000,
                'stok' => 12,
                'gambar' => 'book3.png',
                'deskripsi' => "Parable menggambarkan perjalanan hidup, cinta, dan pencarian makna diri melalui kisah yang sederhana namun penuh refleksi.",
            ],
            [
                'judul' => 'Seporsi Mie Ayam Sebelum Mati',
                'penulis' => 'Brian Khrisna',
                'penerbit' => 'Loveable',
                'tahun_terbit' => 2021,
                'harga' => 125000,
                'stok' => 1,
                'gambar' => 'book4.png',
                'deskripsi' => "Seporsi Mie Ayam Sebelum Mati menggambarkan kisah tentang hidup, kehilangan, dan makna sederhana dari kebahagiaan sebelum segalanya berakhir.",
            ],
            [
                'judul' => 'Rumah Lebah',
                'penulis' => 'Ruwi Meita',
                'penerbit' => 'Gagas Media',
                'tahun_terbit' => 2018,
                'harga' => 0, // Gratis
                'stok' => 4,
                'gambar' => 'book5.png',
                'deskripsi' => "Rumah Lebah menggambarkan kisah misteri dan rahasia kelam yang tersembunyi di balik sebuah tempat, penuh ketegangan dan teka-teki.",
            ],
        ];

        foreach ($books as $buku) {
            Book::create($buku);
        }

        // Kategori
        $categories = ['Fiksi', 'Pengembangan Diri', 'Politik', 'Misteri'];
        foreach ($categories as $cat) {
            \App\Models\KategoriBuku::create(['nama_kategori' => $cat]);
        }
    }
}
