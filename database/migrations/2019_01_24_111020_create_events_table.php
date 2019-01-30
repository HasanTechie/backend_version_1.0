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
            $table->string('id')->unique();
            $table->text('url')->nullable();
            $table->double('standard_price');
            $table->double('standard_price_including_fees');
            $table->string('venue_name')->nullable();
            $table->string('venue_address')->nullable();
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
