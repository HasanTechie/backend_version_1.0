<?php

use Illuminate\Database\Seeder;

class GatheringHotelAllDetailsUsingGooglePlacesDetailsAPISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $key = 'AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg';
        $client = new \GuzzleHttp\Client();

        $hotels = DB::table('hotels')->select('hotel_id')->get();

        foreach ($hotels as $hotel) {
            $hotel_id = $hotel->hotel_id;
            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?placeid=$hotel_id&key=$key");

            $response = json_decode($response->getBody());

            if (!empty($response->result)) {
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
                        'website' => (isset($response->website) ? $response->website : 'Not Available'),
                        'phone' => (isset($response->formatted_phone_number) ? $response->formatted_phone_number : 'Not Available'),
                        'international_phone' => (isset($response->international_phone_number) ? $response->international_phone_number : 'Not Available'),
                        'country' => (isset($address_components['country']) ? $address_components['country'] : 'Not Available'),
                        'locality' => (isset($address_components['locality']) ? $address_components['locality'] : 'Not Available'),
                        'sublocality' => (isset($address_components['sublocality']) ? $address_components['sublocality'] : 'Not Available'),
                        'route' => (isset($address_components['route']) ? $address_components['route'] : 'Not Available'),
                        'street_number' => (isset($address_components['street_number']) ? $address_components['street_number'] : 'Not Available'),
                        'postal_code' => (isset($address_components['postal_code']) ? $address_components['postal_code'] : 'Not Available'),
                        'address_components' => (isset($response->address_components) ? serialize($response->address_components) : 'Not Available'),
                        'opening_hours' => (isset($response->opening_hours) ? serialize($response->opening_hours) : 'Not Available'),
                        'photos' => (isset($response->photos) ? serialize($response->photos) : 'Not Available'),
                        'reviews' => (isset($response->reviews) ? serialize($response->reviews) : 'Not Available'),
                        'adr_address' => (isset($response->adr_address) ? $response->adr_address : 'Not Available'),
                        'maps_url' => (isset($response->url) ? $response->url : 'Not Available'),
                        'vicinity' => (isset($response->vicinity) ? $response->vicinity : 'Not Available'),
                        'total_ratings' => (isset($response->user_ratings_total) ? $response->user_ratings_total : 0),
                        'rating' => (isset($response->rating) ? $response->rating : 0),
                        'name' => (isset($response->name) ? $response->name : 'Not Available'),
                        'unk_id' => (isset($response->id) ? $response->id : 'Not Available'),
                        'address' => (isset($response->formatted_address) ? $response->formatted_address : 'Not Available'),
                        'geometry' => (isset($response->geometry) ? serialize($response->geometry) : 'Not Available'),
                        'plus_code' => (isset($response->plus_code) ? serialize($response->plus_code) : 'Not Available'),
                        'rating' => (isset($response->rating) ? $response->rating : 'Not Available'),
                        'all_data' => (isset($response) ? serialize($response) : 'Not Available'),

                    ]);
            }
        }
    }
}
