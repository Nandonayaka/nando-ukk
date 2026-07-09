<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'nama_lengkap', 'alamat', 'pfp'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function ulasanBukus()
    {
        return $this->hasMany(UlasanBuku::class);
    }

    public function koleksiPribadi()
    {
        return $this->hasMany(KoleksiPribadi::class);
    }

    public function inboxMessages()
    {
        return $this->hasMany(InboxMessage::class)->orderBy('created_at', 'desc');
    }

    public function unreadInboxCount()
    {
        return $this->inboxMessages()->where('is_read', false)->count();
    }
}
