<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_author')->nullable();
            $table->longText('post_content')->nullable();
            $table->text('post_title')->nullable();
            $table->text('post_name')->nullable();
            $table->text('post_excerpt')->nullable();
            $table->string('post_status', 20)->nullable();
            $table->string('comment_status', 20)->nullable();
            $table->string('ping_status', 20)->nullable();
            $table->string('post_type', 20)->nullable();
            $table->string('post_mime_type', 20)->nullable();
            $table->bigInteger('comment_count')->nullable();
            $table->string('image', 255)->nullable();
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
