<?php

use Illuminate\Database\Seeder;

use GuzzleHttp\Client;

class GatheringTicketMasterAPIDiscoveryEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $apiArray = Array(
            "jjSpfuTbaXPnacVm2YGopbIIaf7A8NFG",
            "Q7VcIDLY2qRMsGqlwNJVU4UWDMDNaXcv",
            "dgIOGoQ4AcSAOApXx59AuXI53bKqKTpW",
            "9U3YZf3HN3CCEaAIje3mOGK9o5ouAUyB"
        );


        $k = 0;

        $events = DB::table('events')->select('*')->where('s_no', '>', 3770)->get();

        $requestCount = 0;

        foreach ($events as $event) {

            $url = "https://app.ticketmaster.com/discovery/v2/events/$event->id.json?&apikey=$apiArray[$k]";


            echo $url . "\n";

            try {
                usleep(500000);
                $client = new Client();
                $response = $client->request('GET', $url);

                $response = json_decode($response->getBody());

            } catch (\Exception $ex) {
                if (!empty($ex)) {
                    echo 'Incompleted' . "\n";
                    $response = '';
                }
            }

            if (!empty($response)) {


                $standardPriceMin = 0;
                $standardPriceMax = 0;
                $standardPriceIncludingFeesMin = 0;
                $standardPriceIncludingFeesMax = 0;
                $currency = '';

                if (isset($response->priceRanges)) {


                    foreach ($response->priceRanges as $price) {
                        if (isset($price->type)) {
                            if ($price->type == 'standard') {
                                if (isset($price->min)) {
                                    $standardPriceMin = $price->min;
                                }
                                if (isset($price->max)) {
                                    $standardPriceMax = $price->max;
                                    $currency = $price->currency;
                                }
                            }
                            if ($price->type == 'standard including fees') {
                                if (isset($price->min)) {
                                    $standardPriceIncludingFeesMin = $price->min;
                                }
                                if (isset($price->max)) {
                                    $standardPriceIncludingFeesMax = $price->max;
                                    $currency = $price->currency;
                                }
                            }
                        }
                    }
                }
                if (isset($response->_embedded->venues[0]->address)) {

                    if (isset($response->_embedded->venues[0]->address)) {
                        if (isset($response->_embedded->venues[0]->address->line1)) {
                            $address = $response->_embedded->venues[0]->address->line1;
                        } else {
                            $address = $response->_embedded->venues[0]->address;
                        }
                        if (isset($response->_embedded->venues[0]->address->line2)) {
                            $address = $response->_embedded->venues[0]->address->line1 . $response->_embedded->venues[0]->address->line2;
                        }
                    }
                } else {
                    $address = null;
                }

                DB::table('events')
                    ->where('id', $response->id)
                    ->update([
                        'standard_price_min' => $standardPriceMin,
                        'standard_price_max' => $standardPriceMax,
                        'standard_price_including_fees_min' => $standardPriceIncludingFeesMin,
                        'standard_price_including_fees_max' => $standardPriceIncludingFeesMax,
                        'currency' => $currency,
                        'venue_name' => $response->_embedded->venues[0]->name,
                        'venue_address' => $address,
                        'city' => $response->_embedded->venues[0]->city->name,
                        'all_data' => serialize($response)
                    ]);


                echo ++$requestCount . ' Completed ' . $event->s_no . ' ' . $event->id . "\n";

            }
        }


        /*
        // for gathering basic events data of all countries.
        $countries = DB::table('countries')->select(['iso_code', 'name'])->distinct()->get();

        // For Gathering all Events in Germany
        $j = 0;
        $k = 0;
//        $countryCode = 'DE';
//        $countryName = 'Germany';
        $requestCount = 0;
        foreach ($countries as $country) {


            $countryCode = $country->iso_code;
            $countryName = $country->name;

            for ($i = 0; $i <= 5; $i++) {

//            if ($i % 5 == 0 && $i != 0) {
//                $k++;
//            }

                $url = "https://app.ticketmaster.com/discovery/v2/events.json?countryCode=$countryCode&apikey=$apiArray[$k]&page=$i&size=200";

                echo $url . "\n";

                try {
//                usleep(500000);
                    $client = new Client();
                    $response = '';
                    $response = $client->request('GET', $url);

                    $response = json_decode($response->getBody());

                } catch (\Exception $ex) {
                    if (!empty($ex)) {
                        echo 'Incompleted' . "\n";
                        $response = '';
                    }
                }

                if (!empty($response)) {

                    if ((isset($response->_embedded->events)) && ($response->page->totalElements > 0)) {

                        foreach ($response->_embedded->events as $event) {

                            $standardPriceMin = 0;
                            $standardPriceMax = 0;
                            $standardPriceIncludingFeesMin = 0;
                            $standardPriceIncludingFeesMax = 0;

                            if (isset($event->priceRanges)) {


                                foreach ($event->priceRanges as $price) {
                                    if (isset($price->type)) {
                                        if ($price->type == 'standard') {
                                            if (isset($price->max)) {
                                                $standardPriceMax = $price->max;
                                            }
                                        }
                                        if ($price->type == 'standard including fees') {
                                            if (isset($price->max)) {
                                                $standardPriceIncludingFeesMax = $price->max;
                                            }
                                        }
                                    }
                                }
                            }

//                                if (!(DB::table('events')->where('id', '=', $event->id)->exists())) {

                            DB::table('events')->insert([
                                'uid' => uniqid(),
                                's_no' => ++$j,

                                'name' => isset($event->name) ? ($event->name) : null,
                                'id' => isset($event->id) ? ($event->id) : null,
                                'url' => isset($event->url) ? ($event->url) : null,
                                'standard_price_min' => $standardPriceMin,
                                'standard_price_max' => $standardPriceMax,
                                'standard_price_including_fees_min' => $standardPriceIncludingFeesMin,
                                'standard_price_including_fees_max' => $standardPriceIncludingFeesMax,
                                'country' => $countryName,

                                'all_data' => serialize($event),

                                'source' => 'app.ticketmaster.com/discovery/v2/events',
                                'created_at' => DB::raw('now()'),
                                'updated_at' => DB::raw('now()')
                            ]);

                            echo 'events ' . $j . "\n";
                        }
                    } else {
                        break;
                    }
                    echo ++$requestCount . ' $reponse not empty' . "\n";
                }
            }
        }
        */


    }
}
