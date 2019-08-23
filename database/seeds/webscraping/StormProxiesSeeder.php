<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;

class StormProxiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $dA = [];

    public function run()
    {
        //
        $url = 'https://api.myip.com/';
        $url = 'https://www.hrs.com/hotelData.do?hotelnumber=4982&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=EUR&startDateDay=01&startDateMonth=05&startDateYear=2019&endDateDay=02&endDateMonth=05&endDateYear=2019&adults=1&singleRooms=1&doubleRooms=0&children=0#priceAnchor';

        $this->dA['proxy'] =
            ['163.172.48.109:15005',
                '163.172.48.117:15005',
                '163.172.48.119:15005',
                '163.172.48.121:15005',
                '163.172.48.109:15006',
                '163.172.48.117:15006',
                '163.172.48.119:15006',
                '163.172.48.121:15006',
                '163.172.48.109:15007',
                '163.172.48.117:15007',
                '163.172.48.119:15007',
                '163.172.48.121:15007',
                '163.172.48.109:15008',
                '163.172.48.117:15008',
                '163.172.48.119:15008',
                '163.172.48.121:15008',
                '163.172.36.181:15005',
                '163.172.36.191:15005',
                '62.210.251.228:15005',
                '163.172.36.207:15005',
                '163.172.36.181:15006',
                '163.172.36.191:15006',
                '62.210.251.228:15006',
                '163.172.36.207:15006',
                '163.172.36.181:15007',
                '163.172.36.191:15007',
                '62.210.251.228:15007',
                '163.172.36.207:15007',
                '163.172.36.181:15008',
                '163.172.36.191:15008',
                '62.210.251.228:15008',
                '163.172.36.207:15008'
            ];

        dd(count($this->dA['proxy']) - 1);

        while (0 == 0) {
            $started = microtime(true);
            $client = PhantomClient::getInstance();
            $client->getEngine()->setPath(base_path() . '/bin/phantomjs');
            $client->getEngine()->addOption('--load-images=false');
            $client->getEngine()->addOption('--ignore-ssl-errors=true');
            $client->getEngine()->addOption("--proxy=http://" . $this->dA['proxy'][mt_rand(0, 31)]);
            $client->isLazy(); // Tells the client to wait for all resources before rendering
            $request = $client->getMessageFactory()->createRequest($url);
            $request->setTimeout(80000);
            $response = $client->getMessageFactory()->createResponse();
            // Send the request
            $client->send($request, $response);
            $crawler = new Crawler($response->getContent());

           /* if (!empty($crawler->text())) {
                $end = microtime(true);

                //Calculate the difference in microseconds.
                $difference = $end - $started;

                //Format the time so that it only shows 10 decimal places.
                $queryTime = number_format($difference, 10);

                //Print out the seconds it took for the query to execute.
                echo $crawler->text() . ' ' . Carbon::now()->toDateTimeString() . " SQL query took $queryTime seconds." . "\n";
            } else {
                echo 'empty : ' . $response->getStatus() . "\n";
            }*/
            echo $response->getStatus() . "\n";
        }
    }
}
