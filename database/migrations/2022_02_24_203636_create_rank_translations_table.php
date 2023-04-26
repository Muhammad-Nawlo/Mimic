<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rank_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title');
            $table->timestamps();
            $table->unique(['rank_id', 'locale']);
            $table->foreign('rank_id')->references('id')->on('ranks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rank_translations');
    }
}
