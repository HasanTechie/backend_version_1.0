<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsPricesHotelAquaeductusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('z_ignore_rooms_prices_hotel_aquaeductus', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('day')->nullable();
            $table->string('month')->nullable();
            $table->integer('year')->nullable();
            $table->string('room')->nullable();
            $table->string('price')->nullable();
            $table->text('description')->nullable();
            $table->text('facilities')->nullable();
            $table->string('hotel_name')->nullable();
            $table->text('hotel_address')->nullable();
            $table->string('hotel_phone')->nullable();
            $table->string('hotel_email')->nullable();
            $table->string('hotel_website')->nullable();
            $table->string('rid')->unique();

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
        Schema::dropIfExists('z_ignore_rooms_prices_hotel_aquaeductus');
    }
}
