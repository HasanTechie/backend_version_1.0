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
    public function run()
    {
        //
        $url = 'https://api.myip.com/';

        $procies = ['95.211.175.167:13151', '95.211.175.225:13151'];

        while (0 == 0) {
            $client = PhantomClient::getInstance();
            $client->getEngine()->setPath(base_path() . '/bin/phantomjs');
//            $client->getEngine()->addOption('--load-images=false');
//            $client->getEngine()->addOption('--ignore-ssl-errors=true');
            $client->getEngine()->addOption("--proxy=http://" . $procies[mt_rand(0, 1)]);
            $client->isLazy(); // Tells the client to wait for all resources before rendering
            $request = $client->getMessageFactory()->createRequest($url);
            $request->setTimeout(20000);
            $response = $client->getMessageFactory()->createResponse();
            // Send the request
            $client->send($request, $response);
            $crawler = new Crawler($response->getContent());

            if (!empty($crawler->text())) {
                $started = microtime(true);
                $end = microtime(true);

                //Calculate the difference in microseconds.
                $difference = $end - $started;

                //Format the time so that it only shows 10 decimal places.
                $queryTime = number_format($difference, 10);

                //Print out the seconds it took for the query to execute.
                echo $crawler->text() . ' ' . Carbon::now()->toDateTimeString() . " SQL query took $queryTime seconds." . "\n";
            } else {
                echo 'empty : ' . $response->getStatus() . "\n";
            }
        }
    }
}
