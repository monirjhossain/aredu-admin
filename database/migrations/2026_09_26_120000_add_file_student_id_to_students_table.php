<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileStudentIdToStudentsTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('students', 'file_student_id')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->string('file_student_id', 50)
                ->nullable()
                ->unique()
                ->after('st_office_id');
        });
    }

    public function down()
    {
        if (!Schema::hasColumn('students', 'file_student_id')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['file_student_id']);
            $table->dropColumn('file_student_id');
        });
    }
}
