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
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->string('title')->after('user_id');
            $table->string('slug')->unique()->after('title');
            $table->text('content')->after('slug');
            $table->string('featured_image')->nullable()->after('content');
            $table->integer('view_count')->default(0)->after('featured_image');
            $table->boolean('is_published')->default(true)->after('view_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'title', 'slug', 'content', 'featured_image', 'view_count', 'is_published']);
        });
    }
};
