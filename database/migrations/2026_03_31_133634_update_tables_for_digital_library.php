<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update Users Table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nama_lengkap')) {
                $table->string('nama_lengkap')->nullable();
            }
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable();
            }
            // Update role to have default 'peminjam'
            $table->string('role')->default('peminjam')->change();
        });

        // Update Books Table
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'penerbit')) {
                $table->string('penerbit')->nullable();
            }
        });

        // Rename transactions to peminjamans and add fields
        if (Schema::hasTable('transactions')) {
            Schema::rename('transactions', 'peminjamans');
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->date('tanggal_peminjaman')->nullable();
                $table->date('tanggal_pengembalian')->nullable();
                $table->string('status_peminjaman', 50)->default('Pinjam'); // Pinjam, Kembali
            });
        }

        // Create kategoribuku table
        Schema::create('kategoribukus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        // Create kategoribuku_relasi table
        Schema::create('kategoribuku_relasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategoribukus')->onDelete('cascade');
            $table->timestamps();
        });

        // Create ulasanbukus table
        Schema::create('ulasanbukus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->text('ulasan');
            $table->integer('rating');
            $table->timestamps();
        });

        // Create koleksipribadi table
        Schema::create('koleksipribadi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koleksipribadi');
        Schema::dropIfExists('ulasanbukus');
        Schema::dropIfExists('kategoribuku_relasi');
        Schema::dropIfExists('kategoribukus');

        if (Schema::hasTable('peminjamans')) {
            Schema::rename('peminjamans', 'transactions');
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn(['tanggal_peminjaman', 'tanggal_pengembalian', 'status_peminjaman']);
            });
        }

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('penerbit');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama_lengkap', 'alamat']);
        });
    }
};
