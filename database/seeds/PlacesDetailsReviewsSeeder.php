<?php

use Illuminate\Database\Seeder;

class PlacesDetailsReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $places = DB::table('places')->select(['place_id','id'])->get();
        foreach ($places as $place) {

            $place1 = $place->place_id;
            $client = new \GuzzleHttp\Client();
            $response1 = $client->request('GET', "http://tour-pedia.org/api/getPlaceDetails?id=$place1"); //free

            if ($response1->getStatusCode() == 200) {
                $response1 = json_decode($response1->getBody());

//            dd($response1->id);
                $reviewsArray = [];
                $i = 0;

                if (!empty($response1->reviews)) {
                    if (is_array($response1->reviews)) {


                        foreach ($response1->reviews as $id) {
                            if (count($response1->reviews) > 20) {
                                sleep(1);//delay execution by 1 second.
                            }
                            $client = new \GuzzleHttp\Client();
                            $response11 = $client->request('GET', "http://tour-pedia.org/api/getReviewDetails?id=$id"); //free
                            if ($response11->getStatusCode() == 200) {
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
                        'international_phone_number' => (isset($response1->international_phone_number) ? $response1->international_phone_number : 'Not Available'),
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
                echo $place->id . ' ';
            }
        }
    }
}
