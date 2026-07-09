<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GachaReward extends Model
{
    protected $fillable = ['user_id', 'prize_name', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

