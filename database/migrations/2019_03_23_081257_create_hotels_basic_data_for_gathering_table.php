<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsBasicDataForGatheringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels_basic_data_for_gathering', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

//            $table->string('currency')->nullable();
            $table->string('city');
            $table->string('city_id');
            $table->string('country_code');
//            $table->integer('total_results')->nullable();
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
        Schema::dropIfExists('hotels_basic_data_for_gathering');
    }
}
