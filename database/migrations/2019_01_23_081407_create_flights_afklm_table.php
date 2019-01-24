<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsAfklmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights_afklm', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('flight_number');
            $table->double('flight_duration');
            $table->double('number_of_seats_available');
            $table->string('destination_airport');
            $table->string('destination_city');
            $table->string('destination_city_code');
            $table->string('destination_airport_iata');
            $table->string('carrier_name');
            $table->string('carrier_code');
            $table->string('number_of_stops');
            $table->string('equipmenttype_code');
            $table->string('equipmenttype_name');
            $table->string('equipmenttype_acvCode');
            $table->string('cabin_name');
            $table->string('flight_carrier_name');
            $table->string('flight_carrier_code');
            $table->string('selling_class_code');
            $table->string('origin_airport');
            $table->string('origin_city');
            $table->string('origin_city_code');
            $table->string('origin_airport_iata');
            $table->double('transfer_time');
            $table->string('farebase_code');
            $table->double('total_displayPrice');
            $table->double('total_totalPrice');
            $table->double('total_accuracy');
            $table->string('total_passenger_1_type');
            $table->double('total_passenger_1_fare');
            $table->double('total_passenger_1_taxes');
            $table->string('total_passenger_2_type');
            $table->double('total_passenger_2_fare');
            $table->double('total_passenger_2_taxes');
            $table->string('total_passenger_3_type');
            $table->double('total_passenger_3_fare');
            $table->double('total_passenger_3_taxes');
            $table->string('total_passenger_4_type');
            $table->double('total_passenger_4_fare');
            $table->double('total_passenger_4_taxes');
            $table->string('flexibility_waiver');
            $table->string('currency');
            $table->string('display_type');

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
        Schema::dropIfExists('flights_afklm');
    }
}
