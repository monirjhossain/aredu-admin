<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoginFieldsToStudentUniversityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_university', function (Blueprint $table) {
            $table->boolean('portal_type')->default(true)->after('status_id')->comment('1-agent, 0-single');
            $table->string('login_id')->nullable()->after('portal_type');
            $table->string('login_password')->nullable()->after('login_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_university', function (Blueprint $table) {
            $table->dropColumn(['login_id', 'login_password', 'agent_portal']);
        });
    }
}
