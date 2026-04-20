<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCscaFieldsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('has_csca')->default(false)->after('language_cer');
            $table->string('csca_math')->nullable()->after('has_csca');
            $table->string('csca_physics')->nullable()->after('csca_math');
            $table->string('csca_chemistry')->nullable()->after('csca_physics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['has_csca', 'csca_math', 'csca_physics', 'csca_chemistry']);
        });
    }
}
