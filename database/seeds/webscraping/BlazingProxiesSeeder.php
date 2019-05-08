<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;

class BlazingProxiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //
        $url = 'https://api6.ipify.org/?format=json';
        $url = 'http://ip-api.com/json/';
        $url = 'https://api.myip.com/';
//        $url = 'https://publicholidays.de/baden-wurttemberg/2019-dates/';
//        $url ='https://www.schoolholidayseurope.eu/austria/';
//        $url = 'http://www.eurobookings.com/search.html?q=start:2019-04-05;end:2019-04-06;rmcnf:1[2,0];dsti:3023;dstt:1;dsts:Rome;frm:9;sort:0_desc;cur:EUR;stars:0;';
//        $url = 'https://www.hrs.com/en/hotel/Vienna/d-45883/1#container=&locationId=45883&requestUrl=%2Fen%2Fhotel%2FVienna%2Fd-45883&showAlternates=false&toggle=&arrival=2019-04-14&departure=2019-04-15&lang=en&minPrice=false&roomType=double&singleRoomCount=0&doubleRoomCount=1';
        $proxy = '207.229.93.66';

        while (0 == 0) {
            $port = '102' . mt_rand(5, 9);
            echo $port . "\n";
            $client = PhantomClient::getInstance();
            $client->getEngine()->setPath(base_path() . '/bin/phantomjs');
            $client->getEngine()->addOption('--load-images=false');
            $client->getEngine()->addOption('--ignore-ssl-errors=true');
            $client->getEngine()->addOption("--proxy=http://" . $proxy . ":" . $port);
            $client->isLazy(); // Tells the client to wait for all resources before rendering
            $request = $client->getMessageFactory()->createRequest($url);
//            $request->setTimeout(20000);
            $response = $client->getMessageFactory()->createResponse();
            // Send the request
            $client->send($request, $response);
            $crawler = new Crawler($response->getContent());

            if (!empty($crawler->text())) {
                echo $crawler->text() . ' ' . Carbon::now()->toDateTimeString() . "\n";
            } else {
                echo 'empty : ' . $response->getStatus() . "\n";
            }
        }
    }
}
