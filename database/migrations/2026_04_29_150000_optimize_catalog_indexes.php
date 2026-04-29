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
        Schema::table('theses', function (Blueprint $table) {
            $table->index(['is_library_approved', 'is_public', 'status', 'published_at'], 'theses_public_published_idx');
            $table->index(['is_library_approved', 'is_public', 'status', 'public_downloads'], 'theses_public_popular_idx');
        });

        Schema::table('thesis_catalog_events', function (Blueprint $table) {
            $table->index(['thesis_id', 'created_at'], 'thesis_catalog_events_thesis_created_idx');
            $table->index('user_id', 'thesis_catalog_events_user_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_catalog_events', function (Blueprint $table) {
            $table->dropIndex('thesis_catalog_events_thesis_created_idx');
            $table->dropIndex('thesis_catalog_events_user_idx');
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->dropIndex('theses_public_published_idx');
            $table->dropIndex('theses_public_popular_idx');
        });
    }
};
