<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddCampaignLotteryToken extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('campaigns', 'token')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->string('token', 40)->nullable()->unique()->after('name');
            });
        }

        $campaigns = DB::table('campaigns')->whereNull('token')->pluck('id');
        foreach ($campaigns as $id) {
            do {
                $token = Str::lower(Str::random(16));
            } while (DB::table('campaigns')->where('token', $token)->exists());

            DB::table('campaigns')->where('id', $id)->update(['token' => $token]);
        }

        if (!Schema::hasColumn('campaign_claims', 'campaign_id')) {
            Schema::table('campaign_claims', function (Blueprint $table) {
                $table->unsignedBigInteger('campaign_id')->nullable()->after('campaign_offer_id');
                $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            });
        }

        DB::statement('UPDATE campaign_claims c INNER JOIN campaign_offers o ON o.id = c.campaign_offer_id SET c.campaign_id = o.campaign_id WHERE c.campaign_id IS NULL');

        $duplicateMobiles = DB::table('campaign_claims')
            ->select('campaign_id', 'mobile')
            ->whereNotNull('campaign_id')
            ->groupBy('campaign_id', 'mobile')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        if ($duplicateMobiles === 0 && !$this->hasCampaignMobileUnique()) {
            Schema::table('campaign_claims', function (Blueprint $table) {
                $table->unique(['campaign_id', 'mobile']);
            });
        }
    }

    public function down()
    {
        if ($this->hasCampaignMobileUnique()) {
            Schema::table('campaign_claims', function (Blueprint $table) {
                $table->dropUnique(['campaign_id', 'mobile']);
            });
        }

        if (Schema::hasColumn('campaign_claims', 'campaign_id')) {
            Schema::table('campaign_claims', function (Blueprint $table) {
                $table->dropForeign(['campaign_id']);
                $table->dropColumn('campaign_id');
            });
        }

        if (Schema::hasColumn('campaigns', 'token')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropUnique(['token']);
                $table->dropColumn('token');
            });
        }
    }

    private function hasCampaignMobileUnique(): bool
    {
        $index = DB::select("SHOW INDEX FROM campaign_claims WHERE Key_name = 'campaign_claims_campaign_id_mobile_unique'");

        return count($index) > 0;
    }
}
