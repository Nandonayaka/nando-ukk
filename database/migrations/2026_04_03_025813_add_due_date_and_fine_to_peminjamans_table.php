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
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dateTime('tanggal_peminjaman')->nullable()->change();
            $table->dateTime('tanggal_pengembalian')->nullable()->change();
            $table->dateTime('tanggal_jatuh_tempo')->nullable(); // Due date
            $table->decimal('denda', 15, 2)->default(0); // Fine amount
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->date('tanggal_peminjaman')->change();
            $table->date('tanggal_pengembalian')->change();
            $table->dropColumn(['tanggal_jatuh_tempo', 'denda']);
        });
    }
};
