<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdvTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adv_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campaign_id')->unsigned();
            $table->text('small_desc');
            $table->text('full_desc');
            $table->integer('limit')->unsigned();
            $table->integer('price')->unsigned();
            $table->tinyInteger('type')->unsigned()->default(0);
            $table->integer('min_rating')->unsigned();
            $table->timestamps();

            $table->foreign('campaign_id')
                ->references('id')
                ->on('adv_campaigns')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adv_tasks');
    }
}
