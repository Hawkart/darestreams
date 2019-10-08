<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameIdToStatChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stat_channels', function (Blueprint $table) {
            $table->integer('game_id')->unsigned()->nullable();
        });

        Schema::table('stat_channels', function (Blueprint $table) {
            $table->dropColumn(['exist']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stat_channels', function (Blueprint $table) {
            $table->dropColumn(['game_id']);
        });
    }
}
