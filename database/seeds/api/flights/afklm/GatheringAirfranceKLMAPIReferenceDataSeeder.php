<?php

use Illuminate\Database\Seeder;

use GuzzleHttp\Client;

class GatheringAirfranceKLMAPIReferenceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $apiKey = '4kmnf3mnrk5ne5s53hk6xqvx';

        $url1 = 'https://api.klm.com/opendata/flightoffers/reference-data';
        $url2 = 'https://api.klm.com/opendata/flightoffers/available-offers';


        $airline[0] = 'AF';
        $airline[1] = 'KL';
        $j = 0;
        for ($k = 0; $k <= 1; $k++) {

            $currentAirline = $airline[$k];


            $headers = [
                'api-key' => $apiKey,
                'afkl-travel-host' => 'AF',
                'accept-language' => 'en-US',
                'accept' => 'application/hal+json'
            ];

            try {
                $client = new Client();
                $response = $client->request('GET', $url1, [
                    'headers' => $headers
                ]);

                $response = json_decode($response->getBody());


            } catch (\Exception $ex) {
                echo 'Airport_AFKLM =  ' . $j . ' ' . $currentAirline . "\n";

            }
            if (isset($response)) {
                foreach ($response->continents as $continent) {

                    if (isset($continent->countries)) {

                        foreach ($continent->countries as $country) {

                            if (isset($country->states)) {

                                foreach ($country->states as $state) {
                                    foreach ($state->cities as $city) {

                                        if (isset($city->airports)) {

                                            foreach ($city->airports as $airport) {

                                                if (isset($airport)) {

                                                    //
                                                    DB::table('airports_afklm')->insert([
                                                        'uid' => uniqid(),
                                                        's_no' => ++$j,
                                                        'airline' => $currentAirline,
                                                        'airport_iata' => isset($airport->code) ? $airport->code : null,
                                                        'airport_name' => isset($airport->name) ? $airport->name : null,
                                                        'airport_isorigin' => isset($airport->isOrigin) ? $airport->isOrigin : null,
                                                        'city_code' => isset($city->code) ? $city->code : null,
                                                        'city_name' => isset($city->name) ? $city->name : null,
                                                        'city_isorigin' => isset($city->isOrigin) ? $city->isOrigin : null,
                                                        'state_code' => isset($state->code) ? $state->code : null,
                                                        'state_name' => isset($state->name) ? $state->name : null,
                                                        'country_code' => isset($country->code) ? $country->code : null,
                                                        'country_name' => isset($country->name) ? $country->name : null,
                                                        'continent_code' => isset($continent->code) ? $continent->code : null,
                                                        'continent_name' => isset($continent->name) ? $continent->name : null,
                                                        'defaultairport_in_country' => isset($country->settings->defaultAirport) ? $country->settings->defaultAirport : null,
                                                        'poscountry_in_country' => isset($country->settings->posCountry) ? $country->settings->posCountry : null,
                                                        'maximumnumberofseats_in_country' => isset($country->settings->maximumNumberOfSeats) ? $country->settings->maximumNumberOfSeats : null,
                                                        'minimumnumberofadults_in_country' => isset($country->settings->minimumNumberOfAdults) ? $country->settings->minimumNumberOfAdults : null,
                                                        'countryswitchmandatory_in_country' => isset($country->settings->countrySwitchMandatory) ? $country->settings->countrySwitchMandatory : null,
                                                        'source' => 'api.klm.com/opendata/flightoffers/reference-data',
                                                        'created_at' => DB::raw('now()'),
                                                        'updated_at' => DB::raw('now()')
                                                    ]);
                                                    echo 'Airport_AFKLM =  ' . $j . ' ' . $currentAirline . ' (' . (isset($country->name) ? $country->name : ' ') . ')' . ' (' . (isset($city->name) ? $city->name : ' ') . ')' . "\n";
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if (isset($country->cities)) {

                                foreach ($country->cities as $city) {

                                    if (isset($city->airports)) {

                                        foreach ($city->airports as $airport) {

                                            if (isset($airport)) {


                                                DB::table('airports_afklm')->insert([
                                                    'uid' => uniqid(),
                                                    's_no' => ++$j,
                                                    'airline' => $currentAirline,
                                                    'airport_iata' => isset($airport->code) ? $airport->code : null,
                                                    'airport_name' => isset($airport->name) ? $airport->name : null,
                                                    'airport_isorigin' => isset($airport->isOrigin) ? $airport->isOrigin : null,
                                                    'city_code' => isset($city->code) ? $city->code : null,
                                                    'city_name' => isset($city->name) ? $city->name : null,
                                                    'city_isorigin' => isset($city->isOrigin) ? $city->isOrigin : null,
                                                    'state_code' => isset($state->code) ? $state->code : null,
                                                    'state_name' => isset($state->name) ? $state->name : null,
                                                    'country_code' => isset($country->code) ? $country->code : null,
                                                    'country_name' => isset($country->name) ? $country->name : null,
                                                    'continent_code' => isset($continent->code) ? $continent->code : null,
                                                    'continent_name' => isset($continent->name) ? $continent->name : null,
                                                    'defaultairport_in_country' => isset($country->settings->defaultAirport) ? $country->settings->defaultAirport : null,
                                                    'poscountry_in_country' => isset($country->settings->posCountry) ? $country->settings->posCountry : null,
                                                    'maximumnumberofseats_in_country' => isset($country->settings->maximumNumberOfSeats) ? $country->settings->maximumNumberOfSeats : null,
                                                    'minimumnumberofadults_in_country' => isset($country->settings->minimumNumberOfAdults) ? $country->settings->minimumNumberOfAdults : null,
                                                    'countryswitchmandatory_in_country' => isset($country->settings->countrySwitchMandatory) ? $country->settings->countrySwitchMandatory : null,
                                                    'source' => 'api.klm.com/opendata/flightoffers/reference-data',
                                                    'created_at' => DB::raw('now()'),
                                                    'updated_at' => DB::raw('now()')
                                                ]);
                                                echo 'Airport_AFKLM =  ' . $j . ' ' . $currentAirline . ' (' . (isset($country->name) ? $country->name : ' ') . ')' . ' (' . (isset($city->name) ? $city->name : ' ') . ')' . "\n";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


    }
}
