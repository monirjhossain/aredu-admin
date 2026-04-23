<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('student_csca_exams', function (Blueprint $table) {
            $table->unique(['student_id', 'csca_exam_id'], 'student_csca_exams_student_exam_unique');
        });
    }

    public function down(): void
    {
        Schema::table('student_csca_exams', function (Blueprint $table) {
            $table->dropUnique('student_csca_exams_student_exam_unique');
        });
    }
};
