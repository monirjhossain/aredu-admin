<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('students')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'study_level')) {
                $table->string('study_level')->nullable();
            }

            if (!Schema::hasColumn('students', 'preferred_country')) {
                $table->string('preferred_country')->nullable();
            }

            if (!Schema::hasColumn('students', 'study_id')) {
                $table->unsignedBigInteger('study_id')->nullable();
            }

            if (!Schema::hasColumn('students', 'ref')) {
                $table->string('ref')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Non-destructive; keep columns if added on live.
    }
};
