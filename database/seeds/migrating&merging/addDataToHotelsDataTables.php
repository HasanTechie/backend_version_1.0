<?php

use Illuminate\Database\Seeder;

class addDataToHotelsDataTables extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $eurobookingsHotelsSet = DB::table('hotels_eurobookings')->get();

        foreach ($eurobookingsHotelsSet as $instance1) {
            if (DB::table('hotels_eurobookings_data')->where('eurobooking_id', '=', $instance1->eurobooking_id)->doesntExist()) {
                DB::table('hotels_eurobookings_data')->insert([
                    'uid' => $instance1->uid,
                    's_no' => $instance1->s_no,
                    'name' => $instance1->name,
                    'address' => $instance1->address,
                    'total_rooms' => $instance1->total_rooms,
                    'eurobooking_id' => $instance1->eurobooking_id,
                    'photo' => $instance1->photo,
                    'stars_category' => $instance1->stars_category,
                    'ratings_on_tripadvisor' => $instance1->ratings_on_tripadvisor,
                    'total_number_of_ratings_on_tripadvisor' => $instance1->total_number_of_ratings_on_tripadvisor,
                    'reviews_on_tripadvisor' => $instance1->reviews_on_tripadvisor,
                    'ranking_on_tripadvisor' => $instance1->ranking_on_tripadvisor,
                    'badge_on_tripadvisor' => $instance1->badge_on_tripadvisor,
                    'ratings_on_google' => $instance1->ratings_on_google,
                    'total_number_of_ratings_on_google' => $instance1->total_number_of_ratings_on_google,
                    'details' => $instance1->details,
                    'facilities' => $instance1->facilities,
                    'hotel_info' => $instance1->hotel_info,
                    'policies' => $instance1->policies,
                    'city' => $instance1->city,
                    'city_id_on_eurobookings' => $instance1->city_id_on_eurobookings,
                    'country_code' => $instance1->country_code,
                    'phone' => $instance1->phone,
                    'website' => $instance1->website,
                    'latitude_eurobookings' => $instance1->latitude_eurobookings,
                    'longitude_eurobookings' => $instance1->longitude_eurobookings,
                    'latitude_google' => $instance1->latitude_google,
                    'longitude_google' => $instance1->longitude_google,
                    'hid' => $instance1->hid,
                    'hotel_url_on_eurobookings' => $instance1->hotel_url_on_eurobookings,
                    'all_data_google' => $instance1->all_data_google,
                    'source' => $instance1->source,
                    'created_at' => $instance1->created_at,
                    'updated_at' => $instance1->updated_at,
                ]);
                echo Carbon\Carbon::now()->toDateTimeString() . ' Eurobookings Completed hotel-> ' . $instance1->name . "\n";
            } else {
                echo Carbon\Carbon::now()->toDateTimeString() . ' Eurobookings Existeddd hotel-> ' . $instance1->name . "\n";
            }
        }

        $hrsHotelsSet = DB::table('hotels_hrs')->get();

        foreach ($hrsHotelsSet as $instance2) {
            if (DB::table('hotels_hrs_data')->where('hrs_id', '=', $instance2->hrs_id)->doesntExist()) {
                DB::table('hotels_hrs_data')->insert([
                    'uid' => $instance2->uid,
                    's_no' => $instance2->s_no,
                    'name' => $instance2->name,
                    'address' => $instance2->address,
                    'photo' => $instance2->photo,
                    'hrs_id' => $instance2->hrs_id,
                    'city' => $instance2->city,
                    'city_id_on_hrs' => $instance2->city_id_on_hrs,
                    'country_code' => $instance2->country_code,
                    'ratings_on_hrs' => $instance2->ratings_on_hrs,
                    'total_number_of_ratings_on_hrs' => $instance2->total_number_of_ratings_on_hrs,
                    'ratings_on_google' => $instance2->ratings_on_google,
                    'total_number_of_ratings_on_google' => $instance2->total_number_of_ratings_on_google,
                    'location_details' => $instance2->location_details,
                    'surroundings_of_the_hotel' => $instance2->surroundings_of_the_hotel,
                    'sports_leisure_facilities' => $instance2->sports_leisure_facilities,
                    'nearby_airports' => $instance2->nearby_airports,
                    'details' => $instance2->details,
                    'facilities' => $instance2->facilities,
                    'in_house_services' => $instance2->in_house_services,
                    'latitude_hrs' => $instance2->latitude_hrs,
                    'longitude_hrs' => $instance2->longitude_hrs,
                    'latitude_google' => $instance2->latitude_google,
                    'longitude_google' => $instance2->longitude_google,
                    'phone' => $instance2->phone,
                    'website' => $instance2->website,
                    'hotel_url_on_hrs' => $instance2->hotel_url_on_hrs,
                    'hid' => $instance2->hid,
                    'all_data_google' => $instance2->all_data_google,
                    'source' => $instance2->source,
                    'created_at' => $instance2->created_at,
                    'updated_at' => $instance2->updated_at,
                ]);
                echo Carbon\Carbon::now()->toDateTimeString() . ' HRS Completed hotel-> ' . $instance2->name . "\n";
            } else {
                echo Carbon\Carbon::now()->toDateTimeString() . ' HRS Existeddd hotel-> ' . $instance2->name . "\n";
            }
        }
    }
}
