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

//                $places = DB::table('places')->select('place_id')->get();
                    $place = $instance->id;
                    $client = new \GuzzleHttp\Client();
                    $response1 = $client->request('GET', "http://tour-pedia.org/api/getPlaceDetails?id=$place"); //free

                    if ($response1->getStatusCode() == 200) {
                        $response1 = json_decode($response1->getBody());

//            dd($response1->id);
                        $reviewsArray = [];
                        $i = 0;

                        if (!empty($response1->reviews)) {
                            if (is_array($response1->reviews)) {


                                foreach ($response1->reviews as $id) {
                                    $client = new \GuzzleHttp\Client();
                                    $response11 = $client->request('GET', "http://tour-pedia.org/api/getReviewDetails?id=$id"); //free
                                    if ($response1->getStatusCode() == 200) {
                                        $reviewsArray[$i] = json_decode($response11->getBody());
                                        $i++;
                                    }
                                }
                            }
                        }

//            dd(serialize($reviewsArray));

                        DB::table('places')
                            ->where('place_id', $response1->id)
                            ->update([
                                'name' => (isset($response1->name) ? $response1->name : 'Not Available'),
                                'address' => (isset($response1->address) ? $response1->address : 'Not Available'),
                                'phone_number' => (isset($response1->phone_number) ? $response1->phone_number : 'Not Available'),
                                'international_phone_number' => (isset($instance->international_phone_number) ? $response1->international_phone_number : 'Not Available'),
                                'website' => (isset($response1->website) ? $response1->website : 'Not Available'),
                                'category' => (isset($response1->category) ? $response1->category : 'Not Available'),
                                'location' => (isset($response1->location) ? $response1->location : 'Not Available'),
                                'lat' => (isset($response1->lat) ? $response1->lat : null),
                                'lng' => (isset($response1->lng) ? $response1->lng : null),
                                'numReviews' => (isset($response1->numReviews) ? $response1->numReviews : null),
                                'polarity' => (isset($response1->polarity) ? $response1->polarity : null),
                                'details' => (isset($response1->details) ? $response1->details : 'Not Available'),
                                'originalId' => (isset($response1->originalId) ? $response1->originalId : 'Not Available'),
                                'subCategory' => (isset($response1->subCategory) ? $response1->subCategory : 'Not Available'),
                                'source' => 'http://tour-pedia.org',
                                'description' => (isset($response1->description) ? serialize($response1->description) : 'Not Available'),
                                'external_urls' => (isset($response1->external_urls) ? serialize($response1->external_urls) : 'Not Available'),
                                'statistics' => (isset($response1->statistics) ? serialize($response1->statistics) : 'Not Available'),
                                'reviews_ids' => (isset($response1->reviews) ? serialize($response1->reviews) : 'Not Available'),
                                'detailed_reviews' => (isset($reviewsArray) ? serialize($reviewsArray) : 'Not Available'),
                                'all_data_detailed' => serialize($response1)
                            ]);
                    }
                }
            }
        }
    }
}
