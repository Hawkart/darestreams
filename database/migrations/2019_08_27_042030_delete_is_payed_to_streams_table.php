<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteIsPayedToStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->dropColumn(['is_payed']);
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
            $table->tinyInteger('is_payed')->default(0);
        });
    }
}
