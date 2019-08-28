<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AmountToIntegerToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->bigInteger('amount')->unsigned()->default(0)->change();
        });
        Schema::table('accounts', function (Blueprint $table) {
            $table->bigInteger('amount')->unsigned()->default(0)->change();
        });
        Schema::table('streams', function (Blueprint $table) {
            $table->bigInteger('amount_donations')->unsigned()->default(0)->change();
            $table->bigInteger('min_amount_task_before_stream')->unsigned()->default(0)->change();
            $table->bigInteger('min_amount_task_when_stream')->unsigned()->default(0)->change();
            $table->bigInteger('min_amount_donate_task_before_stream')->unsigned()->default(0)->change();
            $table->bigInteger('min_amount_donate_task_when_stream')->unsigned()->default(0)->change();
            $table->bigInteger('min_amount_superbowl_before_stream')->unsigned()->default(0)->change();
            $table->bigInteger('min_amount_superbowl_when_stream')->unsigned()->default(0)->change();
            $table->bigInteger('min_amount_donate_superbowl_before_stream')->unsigned()->default(0)->change();
            $table->bigInteger('min_amount_donate_superbowl_when_stream')->unsigned()->default(0)->change();
            $table->bigInteger('goal_amount_donate_superbowl_activate')->unsigned()->default(0)->change();
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->bigInteger('amount_donations')->unsigned()->default(0)->change();
            $table->bigInteger('min_donation')->unsigned()->default(0)->change();
        });
        Schema::table('votes', function (Blueprint $table) {
            $table->bigInteger('amount_donations')->unsigned()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integer_to_tables', function (Blueprint $table) {
            //
        });
    }
}
