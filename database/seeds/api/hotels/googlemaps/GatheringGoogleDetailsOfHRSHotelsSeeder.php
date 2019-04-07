<?php

use GuzzleHttp\Client as GuzzleClient;

use Illuminate\Database\Seeder;

class GatheringGoogleDetailsOfHRSHotelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $key = 'AIzaSyCnBc_5D1PX2OV6M4kJ0v8KJS8_aW6Z6L4';

        $hotels = DB::table('hotels_hrs')
            ->whereNull('total_number_of_ratings_on_google')
            ->get();

        foreach ($hotels as $hotel) {

            if (isset($hotel->latitude_hrs) && isset($hotel->longitude_hrs)) {
                $client = new GuzzleClient();
                $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json";
                $input = $hotel->name . ' ' . $hotel->city;
                $input = str_replace('-', ' ', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $input))); // Replaces all special characters.
                $fields = "formatted_address,geometry,name,permanently_closed,photos,place_id,plus_code,types,user_ratings_total,price_level,rating";
                $response = $client->request('GET', "$url?input=$input&inputtype=textquery&fields=$fields&locationbias=circle:200@" . $hotel->latitude_hrs . "," . $hotel->longitude_hrs . "&key=$key");

                if (json_decode($response->getBody())->status != 'ZERO_RESULTS') {
                    $response = json_decode($response->getBody())->candidates[0];

                    DB::table('hotels_hrs')
                        ->where('uid', $hotel->uid)
                        ->update([
                            'latitude_google' => isset($response->geometry->location->lat) ? $response->geometry->location->lat : null,
                            'longitude_google' => isset($response->geometry->location->lng) ? $response->geometry->location->lng : null,
                            'ratings_on_google' => isset($response->rating) ? $response->rating : null,
                            'total_number_of_ratings_on_google' => isset($response->user_ratings_total) ? $response->user_ratings_total : null,
                            'all_data_google' => serialize($response),
                        ]);

                } else {
                    Storage::append('hrs/' . 'GoogleIssues' . '/' . $hotel->city . '/GoogleDataNotFound.log', $input . ' lat:' . $hotel->latitude_hrs . ' lng:' . $hotel->longitude_hrs);
                }
            }
        }
    }
}
