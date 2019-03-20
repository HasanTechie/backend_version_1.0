<?php

use Illuminate\Database\Seeder;

class addDataToEurobookingsTables extends Seeder
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

        foreach ($eurobookingsHotelsSet as $instance1){
            if(DB::table('hotels_eurobookings_data')->where('eurobooking_id','=',$instance1->eurobooking_id)->doesntExist()){
                DB::table('hotels_eurobookings_data')->insert([
                    'uid'=>$instance1->uid,
                    's_no'=>$instance1->s_no,
                    'name'=>$instance1->name,
                    'address'=>$instance1->address,
                    'total_rooms'=>$instance1->total_rooms,
                    'eurobooking_id'=>$instance1->eurobooking_id,
                    'photo'=>$instance1->photo,
                    'stars_category'=>$instance1->stars_category,
                    'ratings_on_tripadvisor'=>$instance1->ratings_on_tripadvisor,
                    'total_number_of_ratings_on_tripadvisor'=>$instance1->total_number_of_ratings_on_tripadvisor,
                    'reviews_on_tripadvisor'=>$instance1->reviews_on_tripadvisor,
                    'ranking_on_tripadvisor'=>$instance1->ranking_on_tripadvisor,
                    'badge_on_tripadvisor'=>$instance1->badge_on_tripadvisor,
                    'ratings_on_google'=>$instance1->ratings_on_google,
                    'total_number_of_ratings_on_google'=>$instance1->total_number_of_ratings_on_google,
                    'details'=>$instance1->details,
                    'facilities'=>$instance1->facilities,
                    'hotel_info'=>$instance1->hotel_info,
                    'policies'=>$instance1->policies,
                    'city'=>$instance1->city,
                    'city_id_on_eurobookings'=>$instance1->city_id_on_eurobookings,
                    'country_code'=>$instance1->country_code,
                    'phone'=>$instance1->phone,
                    'website'=>$instance1->website,
                    'latitude_eurobookings'=>$instance1->latitude_eurobookings,
                    'longitude_eurobookings'=>$instance1->longitude_eurobookings,
                    'latitude_google'=>$instance1->latitude_google,
                    'longitude_google'=>$instance1->longitude_google,
                    'hid'=>$instance1->hid,
                    'hotel_url_on_eurobookings'=>$instance1->hotel_url_on_eurobookings,
                    'all_data_google'=>$instance1->all_data_google,
                    'source'=>$instance1->source,
                    'created_at'=>$instance1->created_at,
                    'updated_at'=>$instance1->updated_at,
                ]);
                echo Carbon\Carbon::now()->toDateTimeString() . ' Completed hotel-> ' . $instance1->name . "\n";
            }else{
                echo Carbon\Carbon::now()->toDateTimeString() . ' Existeddd hotel-> ' . $instance1->name . "\n";
            }
        }
    }
}
