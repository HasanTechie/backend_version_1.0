<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hotel_id')->default('Not Available');
            $table->string('unk_id')->default('Not Available');
            $table->string('name')->default('Not Available');
            $table->string('address')->default('Not Available');
            $table->longText('geometry')->nullable('Not Available');
            $table->longText('plus_code')->nullable('Not Available');
            $table->string('phone')->default('Not Available');
            $table->string('country')->default('Not Available');
            $table->double('rating')->default(0);
            $table->double('total_ratings')->default(0);
            $table->longText('all_data')->nullable();
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
        Schema::dropIfExists('hotels');
    }
}
