<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->string('uid')->unique();
            $table->unsignedInteger('s_no');
            $table->string('airline');
            $table->string('airline_id')->nullable();
            $table->string('source_airport')->nullable();
            $table->string('source_airport_id')->nullable();
            $table->string('destination_airport')->nullable();
            $table->string('destination_airport_id')->nullable();
            $table->string('codeshare')->nullable();
            $table->string('stops');
            $table->string('equipment')->nullable();
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
        Schema::dropIfExists('routes');
    }
}
