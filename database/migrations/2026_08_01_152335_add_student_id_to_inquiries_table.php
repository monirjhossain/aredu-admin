<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('inquiries', 'student_id')) {
                $table->dropColumn('student_id');
            }
        });
    }
};
