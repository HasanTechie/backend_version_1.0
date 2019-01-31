<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesHotelBedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries_hotel_beds', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('country_code')->nullable();
            $table->string('iso_code');
            $table->string('name');
            $table->integer('number_of_states')->nullable();
            $table->text('states')->nullable();
            $table->text('all_data')->nullable();

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
        Schema::dropIfExists('countries_hotel_beds');
    }
}
