<?php

use Illuminate\Database\Seeder;

class PlacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $cities = array("Amsterdam", "Barcelona", "Berlin", "Dubai", "London", "Paris", "Rome", "Tuscany");

        foreach ($cities as $city) {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', "http://tour-pedia.org/api/getPlaces?location=$city"); //free but only one result

            if ($response->getStatusCode() == 200) {


                $response = json_decode($response->getBody());

                foreach ($response as $instance) {
                    //place_id,name,address,category,location,lat,lng,numReviews,reviews,polarity,details,originalId,subCategory,source
                    DB::table('places')->insert([
                        'place_id' => (isset($instance->id) ? $instance->id : null),
                        'name' => (isset($instance->name) ? $instance->name : 'Not Available'),
                        'address' => (isset($instance->address) ? $instance->address : 'Not Available'),
                        'category' => (isset($instance->category) ? $instance->category : 'Not Available'),
                        'location' => (isset($instance->location) ? $instance->location : 'Not Available'),
                        'lat' => (isset($instance->lat) ? $instance->lat : null),
                        'lng' => (isset($instance->lng) ? $instance->lng : null),
                        'numReviews' => (isset($instance->numReviews) ? $instance->numReviews : null),
                        'reviews' => (isset($instance->reviews) ? $instance->reviews : 'Not Available'),
                        'polarity' => (isset($instance->polarity) ? $instance->polarity : null),
                        'details' => (isset($instance->details) ? $instance->details : 'Not Available'),
                        'originalId' => (isset($instance->originalId) ? $instance->originalId : 'Not Available'),
                        'subCategory' => (isset($instance->subCategory) ? $instance->subCategory : 'Not Available'),
                        'source' => 'http://tour-pedia.org',
                        'all_data' => serialize($instance),
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                }
            }
        }
    }
}
