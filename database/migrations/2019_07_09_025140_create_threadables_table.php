<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Cmgmyr\Messenger\Models\Models;

class CreateThreadablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threadables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('thread_id')->unsigned();
            //$table->bigInteger('threadable_id')->unsigned();
            //$table->string('threadable_type');
            $table->morphs('threadable');

            $table->foreign('thread_id')
                ->references('id')
                ->on(Models::table('threads'))
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
        Schema::dropIfExists('threadables');
    }
}
