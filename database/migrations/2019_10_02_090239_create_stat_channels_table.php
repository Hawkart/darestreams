<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('provider');
            $table->string('name');
            $table->string('exid');
            $table->tinyInteger('exist')->default(0);
            $table->tinyInteger('top')->default(0);
            $table->tinyInteger('sort')->default(0);
            $table->text('json');
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
        Schema::dropIfExists('stat_channels');
    }
}
