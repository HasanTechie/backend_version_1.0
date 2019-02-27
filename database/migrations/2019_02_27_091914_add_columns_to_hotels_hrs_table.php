<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToHotelsHrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotels_hrs', function (Blueprint $table) {
            //
            $table->string('ratings_on_google')->nullable();
            $table->string('total_number_of_ratings_on_google')->nullable();
            $table->longText('all_data_google')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotels_hrs', function (Blueprint $table) {
            //
        });
    }
}
