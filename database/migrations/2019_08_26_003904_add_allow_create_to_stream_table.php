<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowCreateToStreamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->boolean('allow_task_before_stream')->default(0);
            $table->boolean('allow_task_when_stream')->default(0);
            $table->decimal('min_amount_task_before_stream')->unsigned()->default(0);
            $table->decimal('min_amount_task_when_stream')->unsigned()->default(0);
            $table->decimal('min_amount_donate_task_before_stream')->unsigned()->default(0);
            $table->decimal('min_amount_donate_task_when_stream')->unsigned()->default(0);

            //Super Bowl
            $table->boolean('allow_superbowl_before_stream')->default(0);
            $table->boolean('allow_superbowl_when_stream')->default(0);
            $table->decimal('min_amount_superbowl_before_stream')->unsigned()->default(0);
            $table->decimal('min_amount_superbowl_when_stream')->unsigned()->default(0);
            $table->decimal('min_amount_donate_superbowl_before_stream')->unsigned()->default(0);
            $table->decimal('min_amount_donate_superbowl_when_stream')->unsigned()->default(0);
            $table->decimal('goal_amount_donate_superbowl_activate')->unsigned()->default(0);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['min_amount', 'min_amount_superbowl']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->dropColumn(['allow_task_before_stream', 'allow_task_when_stream', 'min_amount_task_before_stream',
                'min_amount_task_when_stream', 'min_amount_donate_task_before_stream', 'min_amount_donate_task_when_stream',
                'allow_superbowl_before_stream', 'allow_superbowl_when_stream', 'min_amount_superbowl_before_stream',
                'min_amount_superbowl_when_stream', 'min_amount_donate_superbowl_before_stream', 'min_amount_donate_superbowl_when_stream',
                'goal_amount_donate_superbowl_activate']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->decimal('min_amount')->nullable();
            $table->decimal('min_amount_superbowl')->nullable();
        });
    }
}
