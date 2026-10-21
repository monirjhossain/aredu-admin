<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteVideosTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('website_videos')) {
            return;
        }

        Schema::create('website_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 160);
            $table->string('tag', 60)->nullable();
            $table->string('youtube_url', 500);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('website_videos');
    }
}
