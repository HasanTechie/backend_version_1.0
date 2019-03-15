<?php

use GuzzleHttp\Client as GuzzleClient;

use Illuminate\Database\Seeder;

class TestingGooglePlacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $dataArray;

    public function run()
    {
        //
        $this->dataArray['hotel_name'] = 'BB Hotel Roma Tuscolana San Giovanni';
//        $this->dataArray['hotel_name'] = 'B&B The Condottieri';
//        $this->dataArray['hotel_name'] = 'B&B Domus dei Consoli';
        $this->dataArray['city'] = 'Rome';
        $this->dataArray['hotel_latitude'] = 41.87946;
        $this->dataArray['hotel_longitude'] = 12.52575;
        $key = 'AIzaSyCnBc_5D1PX2OV6M4kJ0v8KJS8_aW6Z6L4';
        $client = new GuzzleClient();
        $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json";

        $input = $this->dataArray['hotel_name'] . ' ' . $this->dataArray['city'];

        $fields = "formatted_address,geometry,name,permanently_closed,photos,place_id,plus_code,types,user_ratings_total,price_level,rating";

        $response = $client->request('GET', "$url?input=$input&inputtype=textquery&fields=$fields&locationbias=circle:100@" . $this->dataArray['hotel_latitude'] . "," . $this->dataArray['hotel_longitude'] . "&key=$key");

        if (json_decode($response->getBody())->status != 'ZERO_RESULTS') {

            $response = json_decode($response->getBody())->candidates[0];

            $this->dataArray['ratings_on_google'] = $response->rating;
            $this->dataArray['total_number_of_ratings_on_google'] = $response->user_ratings_total;
            $this->dataArray['google_name'] = $response->name;
            $this->dataArray['google_latitude'] = $response->geometry->location->lat;
            $this->dataArray['google_longitude'] = $response->geometry->location->lng;
            $this->dataArray['all_data_google'] = serialize($response);

            dd($this->dataArray);
        }
    }
}
