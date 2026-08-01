<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'university')) {
                $table->string('university')->nullable()->after('course');
            }
            if (!Schema::hasColumn('inquiries', 'subject')) {
                $table->string('subject')->nullable()->after('university');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('inquiries', 'university')) {
                $table->dropColumn('university');
            }
            if (Schema::hasColumn('inquiries', 'subject')) {
                $table->dropColumn('subject');
            }
        });
    }
};
