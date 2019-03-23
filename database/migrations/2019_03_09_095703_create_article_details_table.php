<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_details', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->string('lang_id');
            $table->string('title');
            $table->text('context');
            $table->foreign('article_id')->references('id')->on('article')->onDelete('cascade');
            $table->unique([
                'article_id',
                'lang_id',
            ], 'article_details_lang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_details');
    }
}
