<?php

use GuzzleHttp\Client as GuzzleClient;

use Illuminate\Database\Seeder;

class GatheringHotelDetailsUsingGooglePlacesAPISeeder extends Seeder
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
        $client = new GuzzleClient();
        $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json";

        $hotels = DB::table('hotels_eurobookings')->get();

        foreach ($hotels as $hotel) {

            $input = $hotel->name . ' ,' . $hotel->city;

            $fields = "formatted_address,geometry,name,permanently_closed,photos,place_id,plus_code,types,user_ratings_total,price_level,rating";

            $response = $client->request('GET', "$url?input=$input&inputtype=textquery&fields=$fields&key=$key");

            $response = json_decode($response->getBody())->candidates[0];

            DB::table('hotels_eurobookings')
                ->where('name', $response->name)
                ->orWhere('address', $response->formatted_address)
                ->update([
                    'latitude' => $response->geometry->location->lat,
                    'longitude' => $response->geometry->location->lng,
                    'ratings_on_google' => $response->rating,
                    'total_number_of_ratings_on_google' => $response->user_ratings_total,
                    'all_data_google' => serialize($response),
                ]);

            dd('check fist');
        }
    }

}

/*
        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=$input&inputtype=textquery&fields=$fields&key=$key"); //free but only one result
        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJ42MzYk1QqEcRhlX0r_-WtI8&fields=name,rating,price_level,formatted_phone_number&key=$key");
        if (!empty($_SESSION['next_page_token'])) {
            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Hotels%20in%20Frankfurt,%20Germany&key=$key&pagetoken=" . $_SESSION['next_page_token'] . "");
        } else {
            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Hotels%20in%20Frankfurt,%20Germany&key=$key");
        }

        $hotels = DB::table('hotels')->select('hotel_id')->get();
        foreach ($hotels as $hotel) {
            $hotel_id = $hotel->hotel_id;
            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?placeid=$hotel_id&key=$key");
            $response = json_decode($response->getBody());
            $response = $response->result;
            $address_components = [];
            foreach ($response->address_components as $componentInstance) {
                foreach ($componentInstance->types as $type)
                    if (!is_array($type)) {
                        if ($type == 'country') {
                            $address_components['country'] = $componentInstance->long_name;
                        }
                        if ($type == 'locality') {
                            $address_components['locality'] = $componentInstance->long_name;
                        }
                        if ($type == 'sublocality') {
                            $address_components['sublocality'] = $componentInstance->long_name;
                        }
                        if ($type == 'route') {
                            $address_components['route'] = $componentInstance->long_name;
                        }
                        if ($type == 'street_number') {
                            $address_components['street_number'] = $componentInstance->long_name;
                        }
                        if ($type == 'postal_code') {
                            $address_components['postal_code'] = $componentInstance->long_name;
                        }
                    }
            }
            DB::table('hotels')
                ->where('hotel_id', $hotel_id)
                ->update([
                    'website' => $response->website,
                    'phone' => $response->formatted_phone_number,
                    'international_phone' => $response->international_phone_number,
                    'country' => $address_components['country'],
                    'locality' => $address_components['locality'],
                    'sublocality' => $address_components['sublocality'],
                    'route' => $address_components['route'],
                    'street_number' => $address_components['street_number'],
                    'postal_code' => $address_components['postal_code'],
                    'address_components' => serialize($response->address_components),
                    'opening_hours' => serialize($response->opening_hours),
                    'photos' => serialize($response->photos),
                    'reviews' => serialize($response->reviews),
                    'adr_address' => $response->adr_address,
                    'maps_url' => $response->url,
                    'vicinity' => $response->vicinity,
                    'total_ratings' => $response->user_ratings_total,
                    'rating' => $response->rating,
                    'name' => $response->name,
                    'unk_id' => $response->id,
                    'address' => $response->formatted_address,
                    'geometry' => serialize($response->geometry),
                    'plus_code' => serialize($response->plus_code),
                    'rating' => $response->rating,
                    'all_data' => serialize($response),
                ]);
        }
*/
