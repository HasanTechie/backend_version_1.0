<?php

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
            $client->getEngine()->addOption("--proxy=http://" . $procies[mt_rand(0,1)]);
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
