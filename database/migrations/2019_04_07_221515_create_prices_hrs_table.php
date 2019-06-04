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
            $table->bigIncrements('id')->unique();

            $table->double('price')->nullable();
            $table->double('price_should')->nullable();
            $table->string('currency')->nullable();
            $table->unsignedTinyInteger('number_of_adults_in_room_request')->nullable()->index();
            $table->date('check_in_date')->nullable()->index();
            $table->date('check_out_date')->nullable()->index();
            $table->longText('basic_conditions')->nullable();
            $table->text('request_url')->nullable();
            $table->unsignedInteger('room_id')->index();
            $table->string('html_price', 255);
            $table->date('request_date')->index();

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
