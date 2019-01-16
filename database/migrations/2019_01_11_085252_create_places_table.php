<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //id,name,address,category,location,lat,lng,numReviews,reviews,polarity,details,originalId,subCategory
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->double('place_id')->nullable();
            $table->string('name')->default('Not Available');
            $table->string('address')->default('Not Available');
            $table->string('category')->default('Not Available');
            $table->string('location')->default('Not Available');
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->integer('numReviews')->nullable();
            $table->text('reviews')->nullable();
            $table->double('polarity')->nullable();
            $table->text('details')->nullable();
            $table->string('originalId')->default('Not Available');
            $table->string('subCategory')->default('Not Available');
            $table->string('phone_number')->default('Not Available');
            $table->string('international_phone_number')->default('Not Available');
            $table->text('website')->nullable();
            $table->text('description')->nullable();
            $table->text('external_urls')->nullable();
            $table->text('statistics')->nullable();
            $table->text('reviews_ids')->nullable();
            $table->longText('detailed_reviews')->nullable();
            $table->longText('all_data_detailed')->nullable();
            $table->longText('all_data')->nullable();
            $table->string('source')->default('Not Available');
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
        Schema::dropIfExists('places');
    }
}
