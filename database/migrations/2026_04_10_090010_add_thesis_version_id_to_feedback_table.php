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
        Schema::table('feedback', function (Blueprint $table) {
            $table->foreignId('thesis_version_id')
                ->nullable()
                ->after('thesis_id')
                ->constrained('thesis_versions')
                ->nullOnDelete();

            $table->index(['thesis_id', 'thesis_version_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropIndex(['thesis_id', 'thesis_version_id']);
            $table->dropForeign(['thesis_version_id']);
            $table->dropColumn('thesis_version_id');
        });
    }
};
