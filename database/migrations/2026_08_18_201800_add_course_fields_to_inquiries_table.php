<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'result')) {
                $table->string('result')->nullable()->after('last_education');
            }
            if (!Schema::hasColumn('inquiries', 'year')) {
                $table->string('year', 20)->nullable()->after('result');
            }
            if (!Schema::hasColumn('inquiries', 'csca_subjects')) {
                $table->text('csca_subjects')->nullable()->after('year');
            }
        });
    }

    public function down()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $columns = array_values(array_filter(['result', 'year', 'csca_subjects'], function ($column) {
                return Schema::hasColumn('inquiries', $column);
            }));

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
