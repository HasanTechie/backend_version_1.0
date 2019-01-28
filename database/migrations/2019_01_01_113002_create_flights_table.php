<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('flight_id')->nullable();
            $table->string('iata_flight_number')->nullable();
            $table->string('flight_status')->nullable();
            $table->string('aircraft_code')->nullable();
            $table->string('aircraft_registration')->nullable();
            $table->string('airline')->nullable();
            $table->string('arrival_airport_scheduled')->nullable();
            $table->string('arrival_airport_initial')->nullable();
            $table->string('arrival_runway_time_initial_date')->nullable();
            $table->string('arrival_runway_time_initial_time')->nullable();
            $table->string('arrival_runway_time_estimated_date')->nullable();
            $table->string('arrival_runway_time_estimated_time')->nullable();
            $table->string('callsign')->nullable();
            $table->string('departure_airport_scheduled')->nullable();
            $table->string('departure_airport_initial')->nullable();
            $table->string('departure_runway_time_initial_date')->nullable();
            $table->string('departure_runway_time_initial_time')->nullable();
            $table->string('departure_runway_time_estimated_date')->nullable();
            $table->string('departure_runway_time_estimated_time')->nullable();
            $table->string('timestamp_processed_date')->nullable();
            $table->string('timestamp_processed_time')->nullable();

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
        Schema::dropIfExists('flights');
    }
}

/*
 * $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('flight_number')->nullable();

            $table->string('flight_status')->nullable();

            $table->string('aircraft_code')->nullable();
            $table->string('aircraft_name')->nullable();
            $table->string('aircraft_registration')->nullable();
            $table->string('aircraft_acvCode')->nullable();
            $table->string('airline')->nullable();

            $table->string('origin_airport')->nullable();
            $table->string('origin_city')->nullable();
            $table->string('origin_airport_iata')->nullable();
            $table->string('departure_date')->nullable();
            $table->string('departure_time')->nullable();

            $table->string('destination_airport')->nullable();
            $table->string('destination_city')->nullable();
            $table->string('destination_airport_iata')->nullable();
            $table->string('arrival_date')->nullable();
            $table->string('arrival_time')->nullable();

            $table->string('carrier_name')->nullable();

            $table->longText('all_data')->nullable();

            $table->string('source');

            $table->timestamps();
 */
