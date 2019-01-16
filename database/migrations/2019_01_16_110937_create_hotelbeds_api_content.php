<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelbedsApiContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotelbeds_api_content', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('whole_request')->nullable();
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
        Schema::dropIfExists('hotelbeds_api_content');
    }
}
