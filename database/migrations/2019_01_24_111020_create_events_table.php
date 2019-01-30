<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('name')->nullable();
            $table->string('id')->nullable();
            $table->text('url')->nullable();
            $table->double('standard_price_min');
            $table->double('standard_price_max');
            $table->double('standard_price_including_fees_min');
            $table->double('standard_price_including_fees_max');
            $table->string('currency')->nullable();
            $table->string('venue_name')->nullable();
            $table->string('venue_address')->nullable();
            $table->string('city')->nullable();
            $table->string('country');
            $table->longText('all_data')->nullable();
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
        Schema::dropIfExists('events');
    }
}
