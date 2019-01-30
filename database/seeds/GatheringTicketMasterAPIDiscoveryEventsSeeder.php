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
            "dgIOGoQ4AcSAOApXx59AuXI53bKqKTpW",
            "Q7VcIDLY2qRMsGqlwNJVU4UWDMDNaXcv",
            "jjSpfuTbaXPnacVm2YGopbIIaf7A8NFG",
            "9U3YZf3HN3CCEaAIje3mOGK9o5ouAUyB"
        );
        /*
        $events = DB::table('events')->select('*')->get();

        foreach ($events as $event) {

            $url = "https://app.ticketmaster.com/discovery/v2/events/$event->id.json?countryCode=DE&apikey=$apiArray[$k]";


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

                DB::table('events')
                    ->where('id', $response->id)
                    ->update(['all_data' => serialize($response)]);


                echo 'Completed' . ' ' . $event->id . "\n";

            }
        }

        */

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

                            $standedPrice = 0;
                            $standedPriceIncludingFees = 0;

                            if (isset($event->priceRanges)) {


                                foreach ($event->priceRanges as $price) {
                                    if (isset($price->type)) {
                                        if ($price->type == 'standard') {
                                            if (isset($price->max)) {
                                                $standedPrice = $price->max;
                                            }
                                        }
                                        if ($price->type == 'standard including fees') {
                                            if (isset($price->max)) {
                                                $standedPriceIncludingFees = $price->max;
                                            }
                                        }
                                    }
                                }

                                if (!(DB::table('events')->where('id', '=', $event->id)->exists())) {

                                    DB::table('events')->insert([
                                        'uid' => uniqid(),
                                        's_no' => ++$j,

                                        'name' => isset($event->name) ? ($event->name) : null,
                                        'id' => isset($event->id) ? ($event->id) : null,
                                        'url' => isset($event->url) ? ($event->url) : null,
                                        'standard_price' => $standedPrice,
                                        'standard_price_including_fees' => $standedPriceIncludingFees,
                                        'country' => $countryName,

                                        'all_data' => serialize($event),

                                        'source' => 'app.ticketmaster.com/discovery/v2/events',
                                        'created_at' => DB::raw('now()'),
                                        'updated_at' => DB::raw('now()')
                                    ]);

                                    echo 'events ' . $j . "\n";
                                } else {
                                    echo 'existed ' . $j . "\n";
                                }
                            }
                        }
                    }else{
                        break;
                    }
                    echo ++$requestCount . ' $reponse not empty' . "\n";
                }
            }
        }


    }
}
