<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntakeToStudentUniversityTable extends Migration
{
    /**
     * Live: php artisan migrate --force
     * Adds nullable intake per application (e.g. March-2026).
     */
    public function up()
    {
        if (Schema::hasColumn('student_university', 'intake')) {
            return;
        }

        Schema::table('student_university', function (Blueprint $table) {
            $table->string('intake', 50)->nullable()->after('subject_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('student_university', 'intake')) {
            return;
        }

        Schema::table('student_university', function (Blueprint $table) {
            $table->dropColumn('intake');
        });
    }
}
