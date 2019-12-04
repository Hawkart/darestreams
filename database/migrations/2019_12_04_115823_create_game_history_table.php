<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_game_history', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('time');
            $table->bigInteger('game_id')->unsigned();

            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->bigInteger('rating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stat_game_history');
    }
}
