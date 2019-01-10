<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToHotels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {

            $table->string('locality')->default('Not Available');
            $table->string('sublocality')->default('Not Available');
            $table->string('route')->default('Not Available');
            $table->string('street_number')->default('Not Available');
            $table->string('postal_code')->default('Not Available');
            $table->string('international_phone')->default('Not Available');
            $table->text('address_components')->nullable();
            $table->text('adr_address')->nullable();
            $table->text('opening_hours')->nullable();
            $table->text('photos')->nullable();
            $table->text('reviews')->nullable();
            $table->text('maps_url')->nullable();
            $table->text('vicinity')->nullable();
            $table->text('website')->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            //
        });
    }
}
