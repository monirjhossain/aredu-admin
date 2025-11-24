<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('passport')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('zip')->nullable();
            $table->string('gender')->nullable();
            $table->string('language_cer')->nullable();
            $table->string('blood')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('study_level')->nullable();
            $table->string('subject')->nullable();
            $table->string('last_education')->nullable();
            $table->string('preferred_subject')->nullable();
            $table->string('ssc_result')->nullable();
            $table->string('hsc_result')->nullable();
            $table->unsignedBigInteger('education_level_id')->nullable();

            $table->string('preferred_country')->nullable();
            $table->string('intake')->nullable();
            
            $table->string('father_name')->nullable();
            $table->string('father_occoupation')->nullable();
            $table->string('father_mobile_no')->nullable();

            $table->string('mother_name')->nullable();
            $table->string('mother_occoupation')->nullable();
            $table->string('mother_mobile_no')->nullable();
            $table->string('responsible_person')->nullable();        
            $table->string('ref')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('study_id')->nullable();
            $table->unsignedBigInteger('university_id')->nullable();
            $table->unsignedBigInteger('resp_person_id')->nullable();
            
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
