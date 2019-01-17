<?php

use Illuminate\Database\Seeder;

class UpdateFlightsData1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //Laminar Data API

        $apiArray = Array(
            'scheduled','completed','airborne'
        );


        foreach($apiArray as $instance) {

            $titles = DB::table('airports')->pluck('ICAO');

            foreach ($titles as $title) {

                $url = "https://api.laminardata.aero/v1/aerodromes/$title/departures?user_key=5a183c1f789682da267a20a54ca91197&status=$instance";

                echo $url . "\n";


                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_TIMEOUT => 300000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        // Set Here Your Requesred Headers
                        'Accept: application/json',
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    if (isset($response)) {
                        $json = json_decode($response);
                    }
                }

                if (isset($json->features)) {
                    if (is_array($json->features)) {
                        foreach ($json->features as $value) {

                            if ($value) {
                                DB::table('hotels')->insert([
                                    'all_data' => serialize($value),
                                    'source' => 'api.laminardata.aero'
                                ]);
                            }
                        }
                    }
                }
            }
        }

    }
}
