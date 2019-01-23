<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesUncompressedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places_uncompressed', function (Blueprint $table) {
            $table->string('uid')->unique();
            $table->increments('s_no');
            $table->double('place_id')->nullable();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('category')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('phone')->nullable();
            $table->text('website')->nullable();
            $table->binary('all_data_detailed')->nullable();
            $table->binary('all_data')->nullable();
            $table->string('source')->nullable();
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
        Schema::dropIfExists('places_uncompressed');
    }
}
