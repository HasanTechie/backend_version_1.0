<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirportsAfklmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports_afklm', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('airline')->nullable();
            $table->string('airport_iata')->nullable();
            $table->string('airport_name')->nullable();
            $table->boolean('airport_isorigin')->nullable();
            $table->string('city_code')->nullable();
            $table->string('city_name')->nullable();
            $table->boolean('city_isorigin')->nullable();
            $table->string('state_code')->nullable();
            $table->string('state_name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('country_name')->nullable();
            $table->string('continent_code')->nullable();
            $table->string('continent_name')->nullable();
            $table->string('defaultairport_in_country')->nullable();
            $table->string('poscountry_in_country')->nullable();
            $table->string('maximumnumberofseats_in_country')->nullable();
            $table->string('minimumnumberofadults_in_country')->nullable();
            $table->string('countryswitchmandatory_in_country')->nullable();

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
        Schema::dropIfExists('airports_afklm');
    }
}
