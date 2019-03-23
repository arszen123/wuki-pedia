<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_language', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('lang_id', 2);
            $table->string('type');
            $table->foreign('user_id')->references('id')->on('user');
            $table->unique([
                'lang_id',
                'type',
            ], 'user_language');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_language');
    }
}
