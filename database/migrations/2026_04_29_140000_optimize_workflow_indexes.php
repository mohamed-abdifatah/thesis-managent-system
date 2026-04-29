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
        Schema::table('proposals', function (Blueprint $table) {
            $table->index(['thesis_id', 'status'], 'proposals_thesis_status_idx');
            $table->index('status', 'proposals_status_idx');
        });

        Schema::table('thesis_versions', function (Blueprint $table) {
            $table->unique(['thesis_id', 'version_number'], 'thesis_versions_thesis_version_unique');
            $table->index(['thesis_id', 'status'], 'thesis_versions_thesis_status_idx');
            $table->index('reviewed_by', 'thesis_versions_reviewed_by_idx');
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->index(['thesis_id', 'created_at'], 'feedback_thesis_created_idx');
            $table->index('user_id', 'feedback_user_idx');
            $table->index('thesis_version_id', 'feedback_version_idx');
        });

        Schema::table('defense_sessions', function (Blueprint $table) {
            $table->unique('thesis_id', 'defense_sessions_thesis_unique');
            $table->index('status', 'defense_sessions_status_idx');
            $table->index('scheduled_at', 'defense_sessions_scheduled_idx');
        });

        Schema::table('committee_members', function (Blueprint $table) {
            $table->unique(['defense_session_id', 'user_id'], 'committee_members_unique');
            $table->index('user_id', 'committee_members_user_idx');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->unique(['defense_session_id', 'user_id'], 'evaluations_unique');
            $table->index('defense_session_id', 'evaluations_session_idx');
        });

        Schema::table('thesis_units', function (Blueprint $table) {
            $table->index(['thesis_id', 'created_by'], 'thesis_units_thesis_creator_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_units', function (Blueprint $table) {
            $table->dropIndex('thesis_units_thesis_creator_idx');
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropUnique('evaluations_unique');
            $table->dropIndex('evaluations_session_idx');
        });

        Schema::table('committee_members', function (Blueprint $table) {
            $table->dropUnique('committee_members_unique');
            $table->dropIndex('committee_members_user_idx');
        });

        Schema::table('defense_sessions', function (Blueprint $table) {
            $table->dropUnique('defense_sessions_thesis_unique');
            $table->dropIndex('defense_sessions_status_idx');
            $table->dropIndex('defense_sessions_scheduled_idx');
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->dropIndex('feedback_thesis_created_idx');
            $table->dropIndex('feedback_user_idx');
            $table->dropIndex('feedback_version_idx');
        });

        Schema::table('thesis_versions', function (Blueprint $table) {
            $table->dropUnique('thesis_versions_thesis_version_unique');
            $table->dropIndex('thesis_versions_thesis_status_idx');
            $table->dropIndex('thesis_versions_reviewed_by_idx');
        });

        Schema::table('proposals', function (Blueprint $table) {
            $table->dropIndex('proposals_thesis_status_idx');
            $table->dropIndex('proposals_status_idx');
        });
    }
};
