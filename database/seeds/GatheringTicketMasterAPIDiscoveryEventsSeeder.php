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
        $apiKey = 'dgIOGoQ4AcSAOApXx59AuXI53bKqKTpW';

        $j = 0;
        for ($i = 0; $i <= 250; $i++) {
            $i++;

            $url = "https://app.ticketmaster.com/discovery/v2/events.json?countryCode=DE&apikey=$apiKey&page=$i&size=200";

            try {
                $client = new Client();
                $response = $client->request('GET', $url);

                $response = json_decode($response->getBody());

            } catch (\Exception $ex) {
                if (!empty($ex)) {
                    echo 'Incompleted';
                }
            }

            if (isset($response)) {


                foreach ($response->_embedded->events as $event) {
                    echo ($event->name) . "\n";
                    echo ($event->id) . "\n";
                    echo ($event->url) . "\n";

                    $standedPrice=0;
                    $standedPriceIncludingFees=0;

                    foreach ($event->priceRanges as $price) {
                        if (isset($price->type)) {
                            if ($price->type == 'standard') {
                                $standedPrice = $price->max . "\n";
                            }
                            if ($price->type == 'standard including fees') {
                                $standedPriceIncludingFees = $price->max . " inf\n";
                            }
                        }
                    }

                    DB::table('events')->insert([
                        'uid' => uniqid(),
                        's_no' => ++$j,

                        'name' => isset($event->name) ? ($event->name) : null ,
                        'id' => isset($event->id) ? ($event->id) : null ,
                        'url' => isset($event->url) ? ($event->url) : null ,
                        'standard_price' => $standedPrice,
                        'standard_price_including_fees' => $standedPriceIncludingFees,

                        'all_data' => serialize($event),

                        'source' => 'app.ticketmaster.com/discovery/v2/events',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                }
            }
        }
    }
}
