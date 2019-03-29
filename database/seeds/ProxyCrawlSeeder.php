<?php

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
        $url = 'http://www.eurobookings.com/search.html?q=start:2019-04-05;end:2019-04-06;rmcnf:1[2,0];dsti:3023;dstt:1;dsts:Rome;frm:9;sort:0_desc;cur:EUR;stars:0;';
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

        $goutteClient = new GoutteClient();
        $guzzleClient = new GuzzleClient(array(
            'curl' => [
                CURLOPT_PROXY => "http://" . $proxy,
            ]
        ));
        $goutteClient->setClient($guzzleClient);
        $crawler = $goutteClient->request('GET', $url);
        dd($crawler->html());
        $response = $goutteClient->getResponse();
    }
}
