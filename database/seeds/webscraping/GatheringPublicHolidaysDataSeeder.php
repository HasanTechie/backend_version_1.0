<?php

use Goutte\Client as GoutteClient;
use Carbon\Carbon;

use Illuminate\Database\Seeder;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;

class GatheringPublicHolidaysDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $s_no;
    protected $name;
    protected $year;
    protected $url;

    public function run()
    {
        //
        $initialUrl = 'https://publicholidays.eu/';

        try {


            if ($results = DB::table('public_holidays')->orderBy('s_no', 'desc')->first()) {
                $this->s_no = $results->s_no;
            } else {
                $this->s_no = 1;
            }

            $this->year = 2021;

            $client = PhantomClient::getInstance();
            $client->isLazy(); // Tells the client to wait for all resources before rendering
            $request = $client->getMessageFactory()->createRequest($initialUrl);
            $request->setTimeout(50000); // Will render page if this timeout is reached and resources haven't finished loading
            $response = $client->getMessageFactory()->createResponse();
            // Send the request
            $client->send($request, $response);
            $crawler = new Crawler($response->getContent());

            $da[$this->year] = $crawler->filter('table > tbody > tr > td > a')->each(function ($node) {

                sleep(2);
                $this->url = $node->attr('href') . $this->year . '-dates/';
                echo $this->url . "\n";
                $client = PhantomClient::getInstance();
                $client->isLazy(); // Tells the client to wait for all resources before rendering
                $request = $client->getMessageFactory()->createRequest($this->url);
                $request->setTimeout(50000); // Will render page if this timeout is reached and resources haven't finished loading
                $response = $client->getMessageFactory()->createResponse();
                // Send the request
                $client->send($request, $response);
                $crawler = new Crawler($response->getContent());

                $this->name = $crawler->filter('span.hero__heading')->text();
                $da1[$this->name] = $crawler->filter('table > tbody > tr')->each(function ($node) {

                    $da['date'] = ($node->filter('td:nth-child(1)')->count() > 0) ? $node->filter('td:nth-child(1)')->text() : null;
                    $da['day'] = ($node->filter('td:nth-child(2)')->count() > 0) ? $node->filter('td:nth-child(2)')->text() : null;
                    $da['holiday'] = ($node->filter('td:nth-child(3)')->count() > 0) ? $node->filter('td:nth-child(3)')->text() : null;

                    if (!empty($da['day'])) {
                        return $da;
                    }
                });
                $da1[$this->name] = array_filter($da1[$this->name]);
                print_r($da1[$this->name]);
                foreach ($da1[$this->name] as $da) {
                    $phid = $da['date'] . $da['day'] . $da['holiday'] . $this->name . $this->year;
                    $phid = substr(str_replace(' ', '', $phid), 0, 254);
                    DB::table('public_holidays')->insert([
                        'uid' => uniqid(),
                        's_no' => $this->s_no++,
                        'country' => $this->name,
                        'holiday' => $da['holiday'],
                        'day' => $da['day'],
                        'date' => $da['date'],
                        'year' => $this->year,
                        'phid' => $phid,
                        'source' => 'https://publicholidays.eu/',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                    echo 'Compelted ' . Carbon::now()->toDateTimeString() . ' ' . $this->name . "\n";
                }
                return $da1;
            });

            dd($da);

        } catch (Exception $e) {
            dd($e->getMessage() . ' ' . $e->getLine());
        }
    }
}
