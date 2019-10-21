<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToAdvTasksUsedAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adv_campaigns', function (Blueprint $table) {
            $table->bigInteger('used_amount')->default(0);
        });

        Schema::table('adv_tasks', function (Blueprint $table) {
            $table->bigInteger('used_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adv_campaigns', function (Blueprint $table) {
            $table->dropColumn(['used_amount']);
        });

        Schema::table('adv_tasks', function (Blueprint $table) {
            $table->dropColumn(['used_amount']);
        });
    }
}
