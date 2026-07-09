<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboxMessage extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'color',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper: Buat notifikasi baru untuk user
     */
    public static function kirim($userId, $type, $title, $message, $icon = 'fas fa-bell', $color = 'blue')
    {
        return self::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'icon'    => $icon,
            'color'   => $color,
            'is_read' => false,
        ]);
    }
}
