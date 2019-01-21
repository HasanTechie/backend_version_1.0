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
            $table->increments('id');
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
