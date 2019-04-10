<?php

use Goutte\Client as GoutteClient;
use Carbon\Carbon;

use Illuminate\Database\Seeder;

class GatheringPublicHolidaysData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $url = 'https://publicholidays.eu/';

        $client = new GoutteClient();
        $crawler = $client->request('GET', $url);


        $crawler->filter('table > tbody > tr > td > a')->each(function ($node) {
            $client = new GoutteClient();
            $crawler = $client->request('GET', $node->attr('href') . '2019-dates/');
            echo $node->attr('href') . " \n";

            $name = $crawler->filter('span.hero__heading')->text();
            $da[$name] = $crawler->filter('table > tbody > tr')->each(function ($node) {

                $da['date'] = ($node->filter('td:nth-child(1)')->count() > 0) ? $node->filter('td:nth-child(1)')->text() : null;
                $da['day'] = ($node->filter('td:nth-child(2)')->count() > 0) ? $node->filter('td:nth-child(2)')->text() : null;
                $da['holiday'] = ($node->filter('td:nth-child(3)')->count() > 0) ? $node->filter('td:nth-child(3)')->text() : null;


                if (!empty($da['day'])) {
                    return $da;
                }
            });

            
            return array_filter($da[$name]);
        });

    }
}
