<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelBedsTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_beds_test', function (Blueprint $table) {
            $table->increments('id');
            $table->binary('all_data')->nullable();
            $table->string('name')->default('Not Available');
            $table->string('country_code')->default('Not Available');
            $table->string('destination_code')->default('Not Available');
            $table->string('address')->default('Not Available');
            $table->string('city')->default('Not Available');
            $table->string('postal_code')->default('Not Available');
            $table->string('website')->default('Not Available');
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
        Schema::dropIfExists('hotel_beds_test');
    }
}
