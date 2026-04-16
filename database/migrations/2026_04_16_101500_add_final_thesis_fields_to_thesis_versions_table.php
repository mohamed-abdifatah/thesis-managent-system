<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('thesis_versions', function (Blueprint $table) {
            $table->boolean('is_final_thesis')->default(false)->after('status');
            $table->timestamp('finalized_at')->nullable()->after('is_final_thesis');
            $table->index(['thesis_id', 'is_final_thesis'], 'thesis_versions_final_lookup_idx');
        });

        $publishedThesisIds = DB::table('theses')
            ->where('is_public', true)
            ->pluck('id');

        foreach ($publishedThesisIds as $thesisId) {
            $finalVersion = DB::table('thesis_versions')
                ->where('thesis_id', $thesisId)
                ->where('status', 'approved')
                ->orderByDesc('version_number')
                ->orderByDesc('id')
                ->first(['id']);

            if (!$finalVersion) {
                continue;
            }

            DB::table('thesis_versions')
                ->where('id', $finalVersion->id)
                ->update([
                    'is_final_thesis' => true,
                    'finalized_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_versions', function (Blueprint $table) {
            $table->dropIndex('thesis_versions_final_lookup_idx');
            $table->dropColumn(['is_final_thesis', 'finalized_at']);
        });
    }
};
