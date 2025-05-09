<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menghapus semua tabel yang terkait dengan kategori
     */
    public function up(): void
    {
        // Disable foreign key checks to allow dropping tables with foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Drop post_category pivot table if exists
        if (Schema::hasTable('post_category')) {
            Schema::dropIfExists('post_category');
        }
        
        // Drop categories table if exists
        if (Schema::hasTable('categories')) {
            Schema::dropIfExists('categories');
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     * Tidak perlu implementasi karena kita memang ingin menghapus tabel-tabel ini
     */
    public function down(): void
    {
        // No need to recreate tables as we're intentionally removing them
    }
};
