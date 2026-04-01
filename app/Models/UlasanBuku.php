<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UlasanBuku extends Model
{
    protected $table = 'ulasanbukus';
    protected $fillable = ['user_id', 'book_id', 'ulasan', 'rating'];
}
