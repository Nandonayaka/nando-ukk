<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    
    protected $fillable = [
        'user_id', 
        'book_id', 
        'tanggal_peminjaman', 
        'tanggal_pengembalian', 
        'tanggal_jatuh_tempo',
        'denda',
        'status_denda',
        'status_peminjaman'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
