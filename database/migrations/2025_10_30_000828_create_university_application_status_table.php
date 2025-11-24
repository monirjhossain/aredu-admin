<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversityApplicationStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_application_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_university_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->tinyInteger('active')->default(0)->comment('0 = not active, 1 = active');
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('university_application_status');
    }
}
