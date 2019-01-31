<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('country_code')->nullable();
            $table->string('name');
            $table->string('capital')->nullable();
            $table->string('currency')->nullable();
            $table->string('fips_code')->nullable();
            $table->integer('iso_numeric')->nullable();
            $table->double('north')->nullable();
            $table->double('south')->nullable();
            $table->double('east')->nullable();
            $table->double('west')->nullable();
            $table->string('continent')->nullable();
            $table->string('continent_code')->nullable();
            $table->string('languages')->nullable();
            $table->string('iso_alpha3')->nullable();
            $table->string('geoname_id')->nullable();


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
        Schema::dropIfExists('countries');
    }
}
