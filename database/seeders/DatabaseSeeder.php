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

        //Akun Peminjam
        $peminjam1 = User::create([
            'name' => 'Budi Peminjam',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'peminjam',
            'nama_lengkap' => 'Budi Santoso',
            'alamat' => 'Surabaya, Indonesia',
        ]);
        
        $peminjam2 = User::create([
            'name' => 'Siti Peminjam',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'peminjam',
            'nama_lengkap' => 'Siti Aminah',
            'alamat' => 'Bandung, Indonesia',
        ]);
            
        $this->call([
            BookSeeder::class,
        ]);
    }
}
