<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('student_csca_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('csca_exam_id');
            $table->string('result');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('csca_exam_id')->references('id')->on('csca_exams')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_csca_exams');
    }
};
