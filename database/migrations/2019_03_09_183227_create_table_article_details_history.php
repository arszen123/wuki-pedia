<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableArticleDetailsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_details_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('base_id')->nullable();
            $table->string('state');
            $table->unsignedBigInteger('reviewer_id')->nullable();
            $table->string('lang_id');
            $table->string('title');
            $table->text('context');
            $table->timestamps();
            $table->foreign('author_id')->references('id')->on('user');
            $table->foreign('article_id')->references('id')->on('article');
            $table->foreign('base_id')->references('id')->on('article_details_history');
            $table->foreign('reviewer_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_details_history');
    }
}
