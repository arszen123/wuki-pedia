<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->string('lang_id');
            $table->string('tag');
            $table->unique([
                'article_id',
                'lang_id',
                'tag',
            ], 'article_details_tag');
        });
        Schema::create('article_tag_history', function (Blueprint $table) {
            $table->unsignedBigInteger('article_details_history_id');
            $table->string('tag');
            $table->foreign('article_details_history_id')->references('id')->on('article_details_history');
            $table->unique([
                'article_details_history_id',
                'tag',
            ], 'article_details_history_tag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('article_tag_history');
    }
}
