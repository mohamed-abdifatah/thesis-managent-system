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
        Schema::table('theses', function (Blueprint $table) {
            $table->foreignId('student_group_id')
                ->nullable()
                ->after('student_id')
                ->constrained('student_groups')
                ->nullOnDelete();
        });

        $theses = DB::table('theses')
            ->select(['id', 'student_id'])
            ->get();

        if ($theses->isEmpty()) {
            return;
        }

        $studentIds = $theses
            ->pluck('student_id')
            ->filter()
            ->unique()
            ->values();

        if ($studentIds->isEmpty()) {
            return;
        }

        $groupIdsByStudentId = DB::table('students')
            ->whereIn('id', $studentIds)
            ->pluck('student_group_id', 'id');

        foreach ($theses as $thesis) {
            $groupId = $groupIdsByStudentId[$thesis->student_id] ?? null;

            if ($groupId) {
                DB::table('theses')
                    ->where('id', $thesis->id)
                    ->update(['student_group_id' => $groupId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theses', function (Blueprint $table) {
            $table->dropForeign(['student_group_id']);
            $table->dropColumn('student_group_id');
        });
    }
};
