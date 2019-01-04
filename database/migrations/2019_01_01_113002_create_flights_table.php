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
            $table->string('flight_id')->default('Not Available');
            $table->string('type')->default('Not Available');
            $table->string('geometry')->default('Not Available');
            $table->string('geometry_type')->default('Not Available');
            $table->string('geometry_coordinates')->default('Not Available');
            $table->string('airline')->default('Not Available');
            $table->string('arrival_airport_scheduled')->default('Not Available');
            $table->string('arrival_airport_actual')->default('Not Available');
            $table->string('arrival_runway_time_initial_date')->default('Not Available');
            $table->string('arrival_runway_time_initial_time')->default('Not Available');
            $table->string('arrival_runway_time_estimated_date')->default('Not Available');
            $table->string('arrival_runway_time_estimated_time')->default('Not Available');
            $table->string('callsign')->default('Not Available');
            $table->string('gate_time_date')->default('Not Available');
            $table->string('gate_time_time')->default('Not Available');
            $table->string('departure_airport_scheduled')->default('Not Available');
            $table->string('departure_airport_actual')->default('Not Available');
            $table->string('departure_runway_time_initial_date')->default('Not Available');
            $table->string('departure_runway_time_initial_time')->default('Not Available');
            $table->string('departure_runway_time_estimated_date')->default('Not Available');
            $table->string('departure_runway_time_estimated_time')->default('Not Available');
            $table->string('flight_status')->default('Not Available');
            $table->string('position_report')->default('Not Available');
            $table->string('iata_flight_number')->default('Not Available');
            $table->string('timestamp_processed_date')->default('Not Available');
            $table->string('timestamp_processed_time')->default('Not Available');
            $table->string('aircraft_code')->default('Not Available');
            $table->string('aircraft_registration')->default('Not Available');
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
