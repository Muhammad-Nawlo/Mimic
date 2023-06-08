<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->date('end_date');
            $table->string('status')->default('pending')->comment("pending accept reject close");
            $table->foreignId('creater_id')->constrained('clients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->string('hashtags')->nullable();
            $table->string('requests')->nullable();
            $table->boolean('feature')->default(false)->comment('false=>not feature true=>feature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challenges');
    }
}
