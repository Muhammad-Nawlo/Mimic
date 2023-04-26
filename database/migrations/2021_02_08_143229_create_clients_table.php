<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('code')->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->bigInteger('rank_id')->unsigned()->nullable();
            $table->string('social_id')->nullable();
            $table->string('cateories_ids')->nullable();
            $table->string('image')->nullable();
            $table->string('device_serial')->nullable();
            $table->string('device_token')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('verified_email')->default(false);
            $table->string('reason')->nullable();
            $table->string('phone')->nullable();
            $table->string('mac_address')->nullable();
            $table->bigInteger('challenge_num')->nullable();
            $table->bigInteger('like_num')->nullable();
            $table->bigInteger('video_num')->nullable();
            $table->bigInteger('invite_num')->nullable();
            $table->string('status')->comment('Blocked UnBlocked Pending')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
