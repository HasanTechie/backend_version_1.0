<?php

use Illuminate\Database\Seeder;

class SingaporeHotelDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $url = 'https://api.jael.ee/datasets/hotels?country=singapore';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "$url"); //free but only one result


        $response = json_decode($response->getBody());

        foreach ($response as $instance) {

            DB::table('hotels')->insert([
                'name' => (isset($instance->name) ? $instance->name : 'Not Available'),
                'total_rooms' => (isset($instance->totalrooms) ? $instance->totalrooms : null),
                'country' => (isset($instance->country) ? $instance->country : 'Not Available'),
                'city' => (isset($instance->address->city) ? $instance->address->city : 'Not Available'),
                'geometry' => serialize($instance->location),
                'address' => $instance->address->street.', '.$instance->address->city.' '.$instance->address->postalcode,
                'postal_code' => $instance->address->postalcode,
                'address_components' => serialize($instance->address),
                'international_phone' => (isset($instance->phone) ? $instance->phone : 'Not Available'),
                'latitude' => $instance->location->latitude,
                'longitude' => $instance->location->longitude,
                'source' => 'https://api.jael.ee/datasets/hotels',
                'all_data' => serialize($instance),
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);
        }
    }
}
