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
            $table->string('city');
            $table->string('country');
            $table->string('IATA');
            $table->string('ICAO');
            $table->double('latitude');
            $table->double('longitude');
            $table->double('altitude');
            $table->double('timezone');
            $table->string('DST');
            $table->string('Tz_database_time_zone');
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
