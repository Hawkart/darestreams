<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;

class AddEnumsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->smallInteger('type')->unsigned()->default(TransactionType::Donation);
            $table->smallInteger('status')->unsigned()->default(TransactionStatus::Created)->change();
        });

        Schema::table('streams', function (Blueprint $table) {
            $table->smallInteger('status')->unsigned()->default(StreamStatus::Created)->change();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->smallInteger('status')->unsigned()->default(TaskStatus::Created)->change();
            $table->text('canceled_reason')->nullable();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['interval_finished', 'check_vote']);
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->string('result')->nullable();
            $table->decimal('amount_donations')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['canceled_reason']);
        });
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn(['result', 'amount_donations']);
        });
    }
}
