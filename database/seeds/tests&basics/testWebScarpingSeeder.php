<?php

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;

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
        $url = "https://example.com/";

//        $client = new GuzzleClient();
////        $client = new \Guzzle\Http\Client();
//        $response = $client->request('GET', $url, [
//            'curl' => [
//                CURLOPT_URL => "http://api.scraperapi.com/?key=8da7c8bdb65fd32e8018bdba4ffc088d&url=".$url,
//                CURLOPT_RETURNTRANSFER => TRUE,
//                CURLOPT_HEADER => FALSE,
//                CURLOPT_HTTPHEADER => array(
//                    "Accept: application/json"
//                )
//            ]
//        ]);
//
//        dd(json_decode($response->getBody()));
//
//        $response = json_decode($response->getBody());
//
//        Storage::put('kayak.html',$response);
//        dd($response);


        for($i=0;$i<5;$i++){

        $ch = curl_init();
        $url = "http://httpbin.org/ip";
        curl_setopt($ch, CURLOPT_URL,
            "http://api.scraperapi.com/?key=8da7c8bdb65fd32e8018bdba4ffc088d&url=".$url."&render=true");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: application/json"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        Storage::append('ip.log',$response);

        }
        dd($response);

//        dd($response);


    }
}
/*        $client = new Client();
        $crawler = $client->request('GET', 'https://global.momondo.com/hotel-search/Berlin,Germany-c9109/2019-02-23/2019-02-24/2adults?fs=price-options=onlinereq&sort=rank_a');
//        $link = $crawler->selectLink('Hotel Policies')->link();
//        $crawler = $client->click($link);

        Storage::put('hotel_details.html', $crawler->html());

        $crawler->filter('h2 > a')->each(function ($node) {
            print $node->text()."\n";
        });*/
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
