<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreviewToStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('streams', function (Blueprint $table) {
            $table->string('preview')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('fake')->default(0)->nullable();
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
            $table->dropColumn(['preview']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fake']);
        });
    }
}
