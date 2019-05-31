<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsPricesEurobookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms_prices_eurobookings', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->double('price')->nullable();
            $table->double('price_should')->nullable();
            $table->string('currency')->nullable();
            $table->string('room')->nullable();
            $table->string('hotel_uid')->nullable();
            $table->string('hotel_name')->nullable();
            $table->string('hotel_eurobooking_id')->nullable();
            $table->longText('short_description')->nullable();
            $table->longText('facilities')->nullable();
            $table->text('photo')->nullable();
            $table->string('number_of_adults_in_room_request')->nullable();
            $table->string('check_in_date')->nullable();
            $table->string('check_out_date')->nullable();
            $table->string('rid', 255)->unique();
            $table->string('request_date');

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
        Schema::dropIfExists('rooms_prices_eurobookings');
    }
}
