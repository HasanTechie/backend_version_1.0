<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->string('uid')->unique();
            $table->increments('s_no');
            $table->string('airline_id')->unique();
            $table->string('name');
            $table->string('alias')->nullable();
            $table->string('iata')->nullable();
            $table->string('icao')->nullable();
            $table->string('callsign')->nullable();
            $table->string('country')->nullable();
            $table->string('active');
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
        Schema::dropIfExists('airlines');
    }
}
