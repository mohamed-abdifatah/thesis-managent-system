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
        Schema::table('thesis_versions', function (Blueprint $table) {
            $table->foreignId('thesis_unit_id')
                ->nullable()
                ->after('thesis_id')
                ->constrained('thesis_units')
                ->nullOnDelete();

            $table->unsignedInteger('unit_number')->nullable()->after('thesis_unit_id');
            $table->index(['thesis_id', 'thesis_unit_id', 'unit_number'], 'thesis_versions_unit_lookup_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_versions', function (Blueprint $table) {
            $table->dropIndex('thesis_versions_unit_lookup_idx');
            $table->dropForeign(['thesis_unit_id']);
            $table->dropColumn(['thesis_unit_id', 'unit_number']);
        });
    }
};
