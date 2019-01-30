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
        $j = 0;
        foreach ($response as $instance) {

            DB::table('hotels_uncompressed')->insert([
                'uid' => uniqid(),
                's_no' => ++$j,
                'name' => (isset($instance->name) ? $instance->name : null),
                'address' => $instance->address->street . ', ' . $instance->address->city . ' ' . $instance->address->postalcode,
                'phone' => (isset($instance->phone) ? $instance->phone : null),
                'city' => (isset($instance->address->city) ? $instance->address->city : null),
                'country' => (isset($instance->country) ? $instance->country : null),
                'latitude' => $instance->location->latitude,
                'longitude' => $instance->location->longitude,
                'all_data' => serialize($instance),
                'source' => 'api.jael.ee/datasets/hotels',
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);

            echo 'singapore api -> hotels_uncompressed ' . $j . "\n";
        }
    }
}
