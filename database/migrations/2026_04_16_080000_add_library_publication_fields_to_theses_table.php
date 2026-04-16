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
            $table->boolean('is_library_approved')->default(false)->after('department_approval_at');
            $table->foreignId('library_approved_by')->nullable()->after('is_library_approved')->constrained('users')->nullOnDelete();
            $table->timestamp('library_approved_at')->nullable()->after('library_approved_by');

            $table->boolean('is_public')->default(false)->after('library_approved_at');
            $table->foreignId('published_by')->nullable()->after('is_public')->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable()->after('published_by');

            $table->text('catalog_notes')->nullable()->after('published_at');
            $table->index(['status', 'is_library_approved', 'is_public'], 'theses_catalog_lookup_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theses', function (Blueprint $table) {
            $table->dropIndex('theses_catalog_lookup_idx');
            $table->dropForeign(['library_approved_by']);
            $table->dropForeign(['published_by']);
            $table->dropColumn([
                'is_library_approved',
                'library_approved_by',
                'library_approved_at',
                'is_public',
                'published_by',
                'published_at',
                'catalog_notes',
            ]);
        });
    }
};
