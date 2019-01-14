<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumns1ToHotels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            //
            $table->double('total_rooms')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('source')->default('Not Available');
            $table->longText('description')->nullable();
            $table->integer('tourpedia_id')->nullable();
            $table->integer('tourpedia_numReviews')->nullable();
            $table->integer('tourpedia_polarity')->nullable();
            $table->longText('tourpedia_reviews')->nullable();
            $table->longText('tourpedia_details')->nullable();
            $table->string('tourpedia_originalId')->default('Not Available');
            $table->longText('tourpedia_external_urls')->nullable();
            $table->longText('tourpedia_statistics')->nullable();
            $table->longText('tourpedia_reviews_ids')->nullable();
            $table->longText('tourpedia_detailed_reviews')->nullable();
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
