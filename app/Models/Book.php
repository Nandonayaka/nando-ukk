<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['judul', 'penulis', 'tahun_terbit', 'deskripsi', 'harga', 'stok', 'gambar'];
}
