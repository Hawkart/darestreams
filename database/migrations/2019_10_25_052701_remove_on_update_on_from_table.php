<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveOnUpdateOnFromTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adv_campaigns', function (Blueprint $table) {
            $table->dropColumn(['from']);
        });

        Schema::table('adv_campaigns', function (Blueprint $table) {
            $table->timestamp('from')->useCurrent($onUpdate = false);
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
            //
        });
    }
}
