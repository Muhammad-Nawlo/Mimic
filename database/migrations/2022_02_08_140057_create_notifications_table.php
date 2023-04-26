<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sender_id')->unsigned()->nullable();
            $table->foreignId('reciver_id')->constrained('clients')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('comment_id')->unsigned()->nullable();
            $table->bigInteger('replay_id')->unsigned()->nullable();
            $table->bigInteger('challenge_id')->unsigned()->nullable();
            $table->bigInteger('video_id')->unsigned()->nullable();
            $table->string('type')->comment('comment replay request video');
            $table->string('status')->nullable()->comment('null 0 reject  1 approve');
            $table->date('read_at')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
