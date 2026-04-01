<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoleksiPribadi extends Model
{
    protected $table = 'koleksipribadi';
    protected $fillable = ['user_id', 'book_id'];
}
