<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('inquiries')
            ->join('users', 'inquiries.user_id', '=', 'users.id')
            ->whereNull('inquiries.branch_id')
            ->whereNotNull('users.branch')
            ->update([
                'inquiries.branch_id' => DB::raw('users.branch'),
            ]);

        $defaultBranchId = DB::table('branches')->orderBy('id')->value('id');

        if ($defaultBranchId) {
            DB::table('inquiries')
                ->whereNull('branch_id')
                ->update(['branch_id' => $defaultBranchId]);
        }
    }

    public function down(): void
    {
        // Non-destructive data backfill; no rollback needed.
    }
};
