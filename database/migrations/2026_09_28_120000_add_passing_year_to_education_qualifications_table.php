<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPassingYearToEducationQualificationsTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('education_qualifications', 'passing_year')) {
            return;
        }

        Schema::table('education_qualifications', function (Blueprint $table) {
            $table->unsignedSmallInteger('passing_year')->nullable()->after('out_of');
        });
    }

    public function down()
    {
        if (!Schema::hasColumn('education_qualifications', 'passing_year')) {
            return;
        }

        Schema::table('education_qualifications', function (Blueprint $table) {
            $table->dropColumn('passing_year');
        });
    }
}
