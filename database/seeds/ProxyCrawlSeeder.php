<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;

use Illuminate\Database\Seeder;

class ProxyCrawlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $url = 'https://api.myip.com/';
//        $url = 'https://publicholidays.de/baden-wurttemberg/2019-dates/';
//        $url ='https://www.schoolholidayseurope.eu/austria/';
//        $url = 'http://www.eurobookings.com/search.html?q=start:2019-04-05;end:2019-04-06;rmcnf:1[2,0];dsti:3023;dstt:1;dsts:Rome;frm:9;sort:0_desc;cur:EUR;stars:0;';
//        $url = 'https://www.hrs.com/en/hotel/Vienna/d-45883/1#container=&locationId=45883&requestUrl=%2Fen%2Fhotel%2FVienna%2Fd-45883&showAlternates=false&toggle=&arrival=2019-04-14&departure=2019-04-15&lang=en&minPrice=false&roomType=double&singleRoomCount=0&doubleRoomCount=1';
        $proxy = 'proxy.proxycrawl.com:9000';


        /*$client = PhantomClient::getInstance();
        $client->getEngine()->addOption('--load-images=false');
        $client->getEngine()->addOption('--ignore-ssl-errors=true');
        $client->getEngine()->addOption("--proxy=http://" . $proxy);
        $client->isLazy(); // Tells the client to wait for all resources before rendering
        $request = $client->getMessageFactory()->createRequest($url);
        $response = $client->getMessageFactory()->createResponse();
        // Send the request
        $client->send($request, $response);
        $content2 = $response->getContent();
        $crawler = new Crawler($content2);

        dd($crawler->html());*/
        $dA['start_date'] = date("Y-m-d", strtotime("+1 day"));
        $dA['end_date'] = date("Y-m-d", strtotime("+120 day"));
        $dA['adults'] = [1, 2];
        $hotels = DB::table('hotels_hrs')->whereIn('city', ['Rome', 'Berlin'])->get();

        dd(count($hotels));
        foreach($hotels as $hotel){
            echo $hotel->city .' ';
        }
        dd('raeched');
        $count = 0;
        while (strtotime($dA['start_date']) <= strtotime($dA['end_date'])) {
            foreach ($hotels as $hotel) {
                foreach ($dA['adults'] as $adult) {
                    echo $count++ . "\n";
                }

            }
            $dA['start_date'] = date("Y-m-d", strtotime("+1 day", strtotime($dA['start_date'])));
        }

        dd($count);

        $full = '99500';
        $cents = '00';
        $price = preg_replace('/' . trim($cents) . '$/', '', $full) . '.' . $cents;
        dd($price);

        dd(date("Y-m-d", strtotime("+1 day")));

        $text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium deloris dolorum et oris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odeveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odeniti et libero maiores nostrum. Aliquoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nuoris dolorum et eveniet in ioris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odlla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odam corporis dolorum et eveniet in iste non nulla odio optio quaerat quasi quo, rem tempora?Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium deleniti et libero maiores nostrum. Aliquam corporis dolorum et eveniet in iste non nulla odio optio quaerat quasi quo, rem temporaLorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium deleniti et libero maiores nostrum. Aliquam corporis dolorum et eveniet in iste non nulla odio optio quaerat quasi quo, rem tempora??';


        dd(substr(str_replace(' ', '', $text), 0, 240));

        while (0 == 0) {
            $client = PhantomClient::getInstance();
            $client->getEngine()->setPath(base_path() . '/bin/phantomjs');
//            $client->getEngine()->addOption('--load-images=false');
//            $client->getEngine()->addOption('--ignore-ssl-errors=true');
            $client->getEngine()->addOption("--proxy=http://" . $proxy);
            $client->isLazy(); // Tells the client to wait for all resources before rendering
            $request = $client->getMessageFactory()->createRequest($url);
            $request->setTimeout(20000);
            $response = $client->getMessageFactory()->createResponse();
            // Send the request
            $client->send($request, $response);
            $crawler = new Crawler($response->getContent());

            if (!empty($crawler->text())) {
                echo $crawler->text() . "\n";
            } else {
                echo 'empty : ' . $response->getStatus() . "\n";
            }
        }
    }
}
