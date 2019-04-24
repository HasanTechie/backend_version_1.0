<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesHrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices_hrs', function (Blueprint $table) {
            $table->increments('id')->unique();

            $table->double('price')->nullable();
            $table->string('currency')->nullable();
            $table->double('price_should')->nullable();
            $table->string('number_of_adults_in_room_request')->nullable();
            $table->string('check_in_date')->nullable();
            $table->string('check_out_date')->nullable();
            $table->longText('basic_conditions')->nullable();
            $table->text('request_url')->nullable();
            $table->unsignedInteger('r_id')->index();
            $table->string('html_price',255);
            $table->string('request_date');

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
        Schema::dropIfExists('prices_hrs');
    }
}
