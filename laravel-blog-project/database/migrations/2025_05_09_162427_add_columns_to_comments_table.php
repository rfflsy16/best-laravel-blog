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
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->after('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->after('post_id')->constrained('comments')->onDelete('cascade');
            $table->text('content')->after('parent_id');
            $table->boolean('is_approved')->default(true)->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'post_id', 'parent_id']);
            $table->dropColumn(['user_id', 'post_id', 'parent_id', 'content', 'is_approved']);
        });
    }
};
