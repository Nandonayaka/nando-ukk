<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pfp')->nullable()->default(null)->change();
        });

        // Also update existing users that might have the default-pfp.png
        DB::table('users')->where('pfp', 'default-pfp.png')->update(['pfp' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pfp')->default('default-pfp.png')->change();
        });
    }
};
