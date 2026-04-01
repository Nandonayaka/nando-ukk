<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBukuRelasi extends Model
{
    protected $table = 'kategoribuku_relasi';
    protected $fillable = ['book_id', 'kategori_id'];
}
