<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameChannelHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_game_channel_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('time');
            $table->bigInteger('game_history_id')->unsigned();
            $table->bigInteger('channel_id')->unsigned();

            $table->foreign('game_history_id')
                ->references('id')
                ->on('stat_game_history')
                ->onDelete('cascade');

            $table->foreign('channel_id')
                ->references('id')
                ->on('stat_channels')
                ->onDelete('cascade');

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
        Schema::dropIfExists('stat_game_channel_history');
    }
}
