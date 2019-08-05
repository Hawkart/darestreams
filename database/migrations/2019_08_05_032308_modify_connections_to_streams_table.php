<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyConnectionsToStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('streams', function (Blueprint $table) {

            if (!Schema::hasColumn('streams', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn(['user_id']);
            }

            if (!Schema::hasColumn('streams', 'channel_id'))
            {
                $table->bigInteger('channel_id')->unsigned();

                $table->foreign('channel_id')
                    ->references('id')
                    ->on('channels')
                    ->onDelete('cascade');
            }
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

            if (Schema::hasColumn('streams', 'channel_id')) {
                $table->dropForeign(['channel_id']);
                $table->dropColumn(['channel_id']);
            }

            if (!Schema::hasColumn('streams', 'user_id')) {
                $table->bigInteger('user_id')->unsigned();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            }
        });
    }
}
