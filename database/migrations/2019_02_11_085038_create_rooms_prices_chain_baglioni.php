<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsPricesChainBaglioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms_prices_chain_baglioni', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('lowest_price')->nullable();
            $table->string('room')->nullable();
            $table->longText('room_all_rates_and_details')->nullable();
            $table->string('hotel_id')->nullable();
            $table->string('hotel_name')->nullable();
            $table->string('hotel_city')->nullable();
            $table->string('hotel_phone')->nullable();
            $table->string('hotel_email')->nullable();
            $table->string('hotel_website')->nullable();
            $table->string('check_in_date')->nullable();
            $table->string('check_out_date')->nullable();
            $table->string('rid')->unique();
            $table->string('requested_date');

            $table->string('source');

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
        Schema::dropIfExists('rooms_prices_chain_baglioni');
    }
}
