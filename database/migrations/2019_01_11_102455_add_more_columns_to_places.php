<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToPlaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            //
            $table->string('phone_number')->default('Not Available');
            $table->string('international_phone_number')->default('Not Available');
            $table->text('website')->nullable();
            $table->text('description')->nullable();
            $table->text('external_urls')->nullable();
            $table->text('statistics')->nullable();
            $table->text('reviews_ids')->nullable();
            $table->longText('detailed_reviews')->nullable();
            $table->longText('all_data_detailed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('places', function (Blueprint $table) {
            //
        });
    }
}
