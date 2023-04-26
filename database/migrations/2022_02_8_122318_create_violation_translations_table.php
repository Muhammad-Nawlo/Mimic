<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViolationTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violation_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('violation_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->unique(['violation_id', 'locale']);
            $table->foreign('violation_id')->references('id')->on('violations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('violation_translations');
    }
}
