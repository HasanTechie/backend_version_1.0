<?php

use Goutte\Client;

use Illuminate\Database\Seeder;

class testWebScarpingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

    }
}
/*
        $url = "https://www.hotel-bb.com/en/booking-hotel-bb-france.htm?txt-search=Tuscany&type=localization-region&arrive=11%2F03%2F2019&pars=12%2F03%2F2019&selectPersonNumber=2&search=";
        $url = "https://www.hotel-bb.es/en/hotel/barcelona-granollers?original_action=%2Fen%2Fhotel%2Fbarcelona-granollers&gps%5Bvalue%5D=41.6140729%2C2.3038705&gps%5Bdistance%5D%5Bfrom%5D=10&arrival_date=03%2F11%2F2019&departure_date=03%2F12%2F2019&destination=Barcelona%20Granollers&internal_keywords=Barcelona%20Granollers&rooms_number=1&adults_number=1&children_number=0&currency_code=EUR";

        echo $url . "\n";
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $data = $crawler->filter('.price-info')->each(function ($node) {
            return $node->text();
        });

        dd($data);
        */
