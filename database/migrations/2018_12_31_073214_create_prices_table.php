<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('checkin');
            $table->string('nights');
            $table->string('baserate');
            $table->string('taxAndOtherFees');
            $table->string('currency');
            $table->string('lastUpdateTime');
            $table->integer('isVisible');
            $table->integer('isComplete');
            $table->string('errorReasons');
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
        Schema::dropIfExists('prices');
    }
}
