<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsEurobookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels_eurobookings', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->unsignedInteger('s_no');

            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('total_rooms')->nullable();
            $table->string('eurobooking_id')->nullable();
            $table->text('photo')->nullable();
            $table->string('stars_category')->nullable();
            $table->string('ratings_on_tripadvisor')->nullable();
            $table->string('total_number_of_ratings_on_tripadvisor')->nullable();
            $table->text('reviews_on_tripadvisor')->nullable();
            $table->string('ranking_on_tripadvisor')->nullable();
            $table->string('badge_on_tripadvisor')->nullable();
            $table->string('ratings_on_google')->nullable();
            $table->string('total_number_of_ratings_on_google')->nullable();
            $table->text('details')->nullable();
            $table->text('facilities')->nullable();
            $table->text('hotel_info')->nullable();
            $table->text('policies')->nullable();

            $table->string('city')->nullable();
            $table->string('city_id_on_eurobookings')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->text('website')->nullable();
            $table->double('latitude_eurobookings')->nullable();
            $table->double('longitude_eurobookings')->nullable();
            $table->double('latitude_google')->nullable();
            $table->double('longitude_google')->nullable();
            $table->string('hid',254)->unique();
            $table->text('hotel_url_on_eurobookings')->nullable();
            $table->longText('all_data_google')->nullable();
            $table->string('source')->nullable();

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
        Schema::dropIfExists('hotels_eurobookings');
    }
}
