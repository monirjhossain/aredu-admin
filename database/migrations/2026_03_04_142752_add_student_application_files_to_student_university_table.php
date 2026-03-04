<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentApplicationFilesToStudentUniversityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::table('student_university', function (Blueprint $table) {
        $table->string('admission_notice')->nullable();
        $table->string('jw_copy')->nullable();
        $table->string('visa_copy')->nullable();
    });
}

public function down()
{
    Schema::table('student_university', function (Blueprint $table) {
        $table->dropColumn(['admission_notice', 'jw_copy', 'visa_copy']);
    });
}
}
