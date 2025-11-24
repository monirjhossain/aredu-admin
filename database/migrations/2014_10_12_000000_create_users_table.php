<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('mobile_no')->nullable();
            $table->integer('role_id')->nullable()->comment('1. Super Admin, 2.Admin, 3.Manager, 4.Branch Manager 5 Account 6 IT Officer 7 Editor 8 Counselor 9 Student');
            $table->unsignedBigInteger('related_to')->nullable()->comment('Which Manager Related');
            $table->string('branch')->nullable();
            $table->bigInteger('office_id')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('signature')->nullable();
            $table->string('profile_pic')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
