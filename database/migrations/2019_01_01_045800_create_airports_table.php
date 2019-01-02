<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('airport_id')->unique();
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('country');
            $table->string('iata')->nullable();
            $table->string('icao')->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->double('altitude');
            $table->double('timezone')->nullable();
            $table->string('dst')->nullable();
            $table->string('tz_database_time_zone')->nullable();
            $table->string('type');
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
        Schema::dropIfExists('airports');
    }
}
