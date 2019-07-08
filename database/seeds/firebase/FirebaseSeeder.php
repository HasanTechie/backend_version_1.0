<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

use Illuminate\Database\Seeder;

class FirebaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/keys/solidps-backend-analysed-data-firebase-adminsdk-duxjt-82f59033cf.json');

        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();

        $database = $firebase->getDatabase();

        $hotels = DB::table('rooms_prices_hotel_novecento')->limit(100)->get();

        $weathers = DB::table('weathers')->where('country', '=', 'Italy')->get();

       /* foreach($hotels as $hotel){
            $database
                ->getReference('hotels_type_key_value')
                ->push([
                    'uid'=> $hotel->uid,
                    's_no'=> $hotel->s_no,
                    'display_price'=> $hotel->display_price,
                    'striked_price'=> $hotel->striked_price,
                    'room'=> $hotel->room,
                    'room_description'=> $hotel->room_description,
                    'hotel_id'=> $hotel->hotel_id,
                    'hotel_name'=> $hotel->hotel_name,
                    'hotel_address'=> $hotel->hotel_address,
                    'hotel_city'=> $hotel->hotel_city,
                    'hotel_phone'=> $hotel->hotel_phone,
                    'hotel_email'=> $hotel->hotel_email,
                    'hotel_website'=> $hotel->hotel_website,
                    'check_in_date'=> $hotel->check_in_date,
                    'check_out_date'=> $hotel->check_out_date,
                    'rid'=> $hotel->rid,
                    'requested_date'=> $hotel->requested_date,
                    'source'=> $hotel->source,
                    'created_at'=> $hotel->created_at,
                    'updated_at'=> $hotel->updated_at,
                ]);
        }*/

        /*foreach($weathers as $weather){

            $database
                ->getReference('weather_type_key_value')
                ->push([
                    'uid'=> $weather->uid,
                    's_no'=> $weather->s_no,
                    'city_id'=> $weather->city_id,
                    'city_cid'=> $weather->city_cid,
                    'city'=> $weather->city,
                    'location_type'=> $weather->location_type,
                    'country'=> $weather->country,
                    'latitude'=> $weather->latitude,
                    'longitude'=> $weather->longitude,
                    'weather_data'=> $weather->weather_data,
                    'source'=> $weather->source,
                    'created_at'=> $weather->created_at,
                    'updated_at'=> $weather->updated_at,
                ]);
        }*/


/*
        $newPost->getKey(); // => -KVr5eu8gcTv7_AHb-3-
        $newPost->getUri(); // => https://my-project.firebaseio.com/blog/posts/-KVr5eu8gcTv7_AHb-3-

        $newPost->getChild('title')->set('Changed post title');
        $newPost->getValue(); // Fetches the data from the realtime database
        $newPost->remove();*/
    }
}
