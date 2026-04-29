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
        $studentRoleId = DB::table('roles')->where('name', 'student')->value('id');
        if (!$studentRoleId) {
            $studentRoleId = DB::table('roles')->insertGetId([
                'name' => 'student',
                'description' => 'University Student',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $defaultDepartmentId = DB::table('departments')->orderBy('id')->value('id');
        if (!$defaultDepartmentId) {
            $defaultDepartmentId = DB::table('departments')->insertGetId([
                'name' => 'General Studies',
                'code' => 'GEN',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('users')->whereNull('role_id')->update(['role_id' => $studentRoleId]);
        DB::table('users')->whereNull('department_id')->update(['department_id' => $defaultDepartmentId]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['department_id']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE users MODIFY role_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE users MODIFY department_id BIGINT UNSIGNED NOT NULL');
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->restrictOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['department_id']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE users MODIFY role_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE users MODIFY department_id BIGINT UNSIGNED NULL');
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
        });
    }
};
