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
        Schema::table('students', function (Blueprint $table) {
            $table->unique('user_id', 'students_user_id_unique');
        });

        Schema::table('supervisors', function (Blueprint $table) {
            $table->unique('user_id', 'supervisors_user_id_unique');
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->unique('student_id', 'theses_student_id_unique');
            $table->index('status', 'theses_status_idx');
            $table->index(['status', 'supervisor_id'], 'theses_status_supervisor_idx');
        });

        Schema::table('student_groups', function (Blueprint $table) {
            $table->index(['department_id', 'academic_year'], 'student_groups_department_year_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_groups', function (Blueprint $table) {
            $table->dropIndex('student_groups_department_year_idx');
        });

        Schema::table('theses', function (Blueprint $table) {
            $table->dropUnique('theses_student_id_unique');
            $table->dropIndex('theses_status_idx');
            $table->dropIndex('theses_status_supervisor_idx');
        });

        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropUnique('supervisors_user_id_unique');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_user_id_unique');
        });
    }
};
