<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlaceToGameHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stat_game_history', function (Blueprint $table) {
            $table->integer('place')->default(0)->unsigned();
        });
        Schema::table('stat_game_channel_history', function (Blueprint $table) {
            $table->integer('place')->default(0)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stat_game_history', function (Blueprint $table) {
            $table->dropColumn(['place']);
        });
        Schema::table('stat_game_channel_history', function (Blueprint $table) {
            $table->dropColumn(['place']);
        });
    }
}
