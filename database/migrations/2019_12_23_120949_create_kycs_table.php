<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKycsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kycs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->unsigned();
            $table->string('first_name');
            $table->string('surname');
            $table->string('full_name');
            $table->string('sex');

            $table->date('date_birth');
            $table->string('city_birth');
            $table->string('state_birth')->nullable();
            $table->string('country_birth');

            $table->string('country_tax');
            $table->string('inn_tax')->nullable();
            $table->string('us_social_number_tax')->nullable();
            $table->string('us_taxpayer_number_tax')->nullable();

            $table->string('home_street_1');
            $table->string('home_street_2')->nullable();
            $table->string('home_city');
            $table->string('home_state')->nullable();
            $table->string('home_zip_code');
            $table->string('home_country');

            $table->boolean('mailing_is_home')->default(true);
            $table->string('mailing_street_1')->nullable();
            $table->string('mailing_street_2')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_zip_code')->nullable();
            $table->string('mailing_country')->nullable();

            $table->string('phone');
            $table->boolean('personal_verified')->default(false);
            $table->boolean('passport_verified')->default(false);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kycs');
    }
}
