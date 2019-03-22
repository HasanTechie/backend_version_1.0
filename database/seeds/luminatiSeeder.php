<?php

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;

use Illuminate\Database\Seeder;

class luminatiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $dataArray;
    public function run()
    {
        //
//        echo 'To enable your free eval account and get CUSTOMER, YOURZONE and '
//            .'YOURPASS, please contact sales@luminati.io';

        $url = 'https://api.myip.com/';
        $url = 'https://www.eurobookings.com/search.html?q=start:2019-04-05;end:2019-04-06;rmcnf:1[2,0];dsti:3023;dstt:1;dsts:Rome;frm:9;sort:0_desc;cur:EUR;stars:0;';
//        $url = 'https://www.eurobookings.com/rome-hotels-it/best-western-hotel-i-triangoli.html?q=start:2019-04-02;end:2019-04-03;rmcnf:1[2,0];dsti:3023;dstt:1;dsts:Rome;frm:1;sort:0_desc;cur:EUR;';
//        $url='https://www.eurobookings.com/prague-hotels-cz/u-zlate-podkovy-at-the-golden-horseshoe.html?q=start:2019-04-02;end:2019-04-03;rmcnf:1[2,0];offset:91;dsti:2872;dstt:1;dsts:Prague;frm:1;sort:0_desc;cur:EUR;';
        $this->dataArray['username'] = 'lum-customer-solidps-zone-static';
        $this->dataArray['password'] = 'azuuy61773vi';
        $this->dataArray['port'] = 22225;
        $this->dataArray['user_agent'] = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';
        $this->dataArray['super_proxy'] = 'zproxy.lum-superproxy.io';
//        $curl = curl_init($url);
//        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_PROXY, "http://$super_proxy:$port");
//        curl_setopt($curl, CURLOPT_PROXYUSERPWD, "$username-session-$session:$password");
//        $result = curl_exec($curl);
//        curl_close($curl);
//        if ($result) {
//            echo $result;
//        }



        $goutteClient = new GoutteClient();
        $guzzleClient = new GuzzleClient(array(
            'curl' => [
                CURLOPT_USERAGENT => $this->dataArray['user_agent'],
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_PROXY => "http://" . $this->dataArray['super_proxy'] . ":" . $this->dataArray['port'],
                CURLOPT_PROXYUSERPWD => $this->dataArray['username'] . "-session-" . mt_rand() . ":" . $this->dataArray['password'],
            ]
        ));
        $goutteClient->setClient($guzzleClient);
        $crawler = $goutteClient->request('GET', $url);
        $response = $goutteClient->getResponse();


/*        $client2 = PhantomClient::getInstance();
        $client2->getEngine()->addOption('--load-images=false');
        $client2->getEngine()->addOption('--ignore-ssl-errors=true');
        $client2->getEngine()->addOption("--proxy=http://" . $this->dataArray['super_proxy'] . ":" . $this->dataArray['port']);
        $client2->getEngine()->addOption("--proxy-auth=" . $this->dataArray['username'] . "-session-" . mt_rand() . ":" . $this->dataArray['password']);
        $client2->isLazy(); // Tells the client to wait for all resources before rendering
        $request = $client2->getMessageFactory()->createRequest($url);
        $request->setTimeout(5000); // Will render page if this timeout is reached and resources haven't finished loading
        $response = $client2->getMessageFactory()->createResponse();
        // Send the request
        $client2->send($request, $response);
        $content2 = $response->getContent();
        $crawler = new Crawler($content2);*/

        dd($response->getStatus());
//        dd($crawler->html());
    }
}
