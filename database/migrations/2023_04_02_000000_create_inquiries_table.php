<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('country')->nullable();
            $table->string('country_preferred')->nullable();
            $table->string('course')->nullable();
            $table->string('last_education')->nullable();
            $table->string('email')->nullable();
            $table->string('oppurtunity')->nullable();
            $table->longText('message')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->tinyInteger('view')->default(0);
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
        Schema::dropIfExists('inquiries');
    }
}
