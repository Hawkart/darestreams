<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatChannelHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_channel_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('channel_id')->unsigned();
            $table->integer('followers')->default(0);
            $table->integer('views')->default(0);
            $table->integer('place')->default(0);
            $table->integer('rating')->default(0);
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
        Schema::dropIfExists('stat_channel_history');
    }
}
