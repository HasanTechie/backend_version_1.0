<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsHrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms_hrs', function (Blueprint $table) {
            $table->increments('id')->unique();

            $table->string('room')->nullable();
            $table->unsignedInteger('hotel_id')->index();
            $table->string('room_type')->nullable();
            $table->string('criteria')->nullable();
            $table->longText('basic_conditions')->nullable();
            $table->text('photo')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('facilities')->nullable();
            $table->string('rid',255)->unique();

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
        Schema::dropIfExists('rooms_hrs');
    }
}
