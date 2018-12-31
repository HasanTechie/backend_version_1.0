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
            $table->string('Checkin');
            $table->string('Nights');
            $table->string('Baserate');
            $table->string('TaxAndOtherFees');
            $table->string('Currency');
            $table->string('LastUpdateTime');
            $table->integer('IsVisible');
            $table->integer('IsComplete');
            $table->string('ErrorReasons');
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
