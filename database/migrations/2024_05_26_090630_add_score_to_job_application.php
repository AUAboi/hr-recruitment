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
        Schema::table('job_applications', function (Blueprint $table) {
            $table->json('score')->nullable();
            $table->unsignedInteger('relavancy_score')->nullable();
            $table->unsignedInteger('experience_score')->nullable();
            $table->unsignedInteger('skill_score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('score');
            $table->dropColumn('relavancy_score');
            $table->dropColumn('experience_score');
            $table->dropColumn('skill_score');
        });
    }
};
