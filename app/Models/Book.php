<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['judul', 'penulis', 'penerbit', 'tahun_terbit', 'deskripsi', 'harga', 'stok', 'gambar'];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function ulasanBukus()
    {
        return $this->hasMany(UlasanBuku::class);
    }

    public function kategoriBukuRelasi()
    {
        return $this->hasMany(KategoriBukuRelasi::class);
    }

    public function kategoris()
    {
        return $this->belongsToMany(KategoriBuku::class, 'kategoribuku_relasi', 'book_id', 'kategori_id');
    }
}
