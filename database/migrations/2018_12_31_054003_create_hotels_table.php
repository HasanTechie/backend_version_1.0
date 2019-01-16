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
            $table->text('website')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->longText('geometry')->nullable('Not Available');
            $table->longText('plus_code')->nullable('Not Available');
            $table->string('phone')->default('Not Available');
            $table->string('country')->default('Not Available');
            $table->double('rating')->default(0);
            $table->double('total_ratings')->default(0);
            $table->longText('all_data')->nullable();
            $table->string('city')->default('Not Available');
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
            $table->double('total_rooms')->nullable();
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
            $table->longText('google_all_data')->nullable();
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
