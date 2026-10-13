<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('campaigns')) {
            Schema::create('campaigns', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('status', 20)->default('active');
                $table->text('note')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('campaign_offers')) {
            Schema::create('campaign_offers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('campaign_id');
                $table->string('label')->nullable();
                $table->unsignedInteger('discount_amount');
                $table->unsignedInteger('max_claims')->default(500);
                $table->string('token', 40)->unique();
                $table->boolean('is_active')->default(true);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();

                $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('campaign_claims')) {
            Schema::create('campaign_claims', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('campaign_offer_id');
                $table->string('name');
                $table->string('mobile', 30);
                $table->string('college_name');
                $table->string('claim_code', 40)->unique();
                $table->unsignedInteger('discount_amount');
                $table->string('status', 20)->default('available');
                $table->timestamp('used_at')->nullable();
                $table->unsignedBigInteger('used_by')->nullable();
                $table->timestamps();

                $table->foreign('campaign_offer_id')->references('id')->on('campaign_offers')->onDelete('cascade');
                $table->unique(['campaign_offer_id', 'mobile']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('campaign_claims');
        Schema::dropIfExists('campaign_offers');
        Schema::dropIfExists('campaigns');
    }
}
