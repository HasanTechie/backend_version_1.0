<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsAfklmUncompressedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights_afklm_uncompressed', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('origin_name');
            $table->string('origin_iata');
            $table->string('destination_name');
            $table->string('destination_iata');
            $table->string('airline_code');
            $table->string('flight_date');
            $table->longText('all_data');

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
        Schema::dropIfExists('flights_afklm_uncompressed');
    }
}
