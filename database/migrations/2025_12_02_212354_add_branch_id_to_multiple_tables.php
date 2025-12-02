<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'finance_entries',
            'inquiries',
            'invoices',
            'sms_templates',
            'students',
            'wallets',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'branch_id')) {
                    $table->unsignedBigInteger('branch_id')->nullable()->after('id');
                }
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'finance_entries',
            'inquiries',
            'invoices',
            'sms_templates',
            'students',
            'wallets',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'branch_id')) {
                    $table->dropColumn('branch_id');
                }
            });
        }
    }
};
