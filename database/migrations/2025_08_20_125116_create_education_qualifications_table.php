<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education_qualifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('education_level_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('institution')->nullable();
            $table->string('group_subject')->nullable();
            $table->string('result')->nullable();
            $table->string('out_of')->nullable();
            // $table->string('passing_year')->nullable();
            $table->date('attend_from')->nullable();
            $table->date('attend_to')->nullable();
            $table->string('country')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('education_qualifications');
    }
}
