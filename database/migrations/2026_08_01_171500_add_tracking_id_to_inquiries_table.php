<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'tracking_id')) {
                $table->string('tracking_id', 50)->nullable()->unique()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('inquiries', 'tracking_id')) {
                $table->dropUnique(['tracking_id']);
                $table->dropColumn('tracking_id');
            }
        });
    }
};
