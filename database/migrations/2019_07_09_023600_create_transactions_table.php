<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('account_sender_id')->unsigned()->nullable();
            $table->bigInteger('account_receiver_id')->unsigned();
            $table->bigInteger('task_id')->unsigned()->nullable();
            $table->decimal('amount')->unsigned();
            $table->tinyInteger('status')->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('account_receiver_id')
                ->references('id')
                ->on('accounts')
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
        Schema::dropIfExists('transactions');
    }
}
