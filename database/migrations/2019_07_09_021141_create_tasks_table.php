<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stream_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->text('description');
            $table->tinyInteger('is_superbowl')->default(0);
            $table->tinyInteger('interval_until_end')->default(0);
            $table->integer('interval_time')->unsigned()->nullable();
            $table->decimal('min_amount')->nullable();
            $table->decimal('min_amount_superbowl')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('interval_finished')->default(0);
            $table->tinyInteger('check_vote')->default(0);
            $table->decimal('amount_donations')->default(0);
            $table->timestamps();

            $table->foreign('stream_id')
                ->references('id')
                ->on('streams')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('tasks');
    }
}
