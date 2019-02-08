<?php

use Goutte\Client;

use Illuminate\Database\Seeder;

class GatheringHotels_bookhypensecure_ScrapingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $client = new Client();

        $url = "https://www.book-secure.com/index.php?s=results&property=itrom31253&arrival=2019-03-07&departure=2019-03-08&adults1=1&children1=0&rooms=1&locale=en_GB&currency=EUR&stid=6th1z0ek1&HLANG=IT&showPromotions=1&Clusternames=ITROMHTLHOTELLATINUM&cluster=ITROMHTLHOTELLATINUM&Hotelnames=ITROMHTLHOTELLATINUM&hname=ITROMHTLHOTELLATINUM&arrivalDateValue=2019-03-07&fromday=7&frommonth=3&fromyear=2019&nbdays=1&nbNightsValue=1&adulteresa=1&nbAdultsValue=1&redir=BIZ-so5523q0o4&rt=1549530330";

        $crawler = $client->request('GET', $url);

//        $crawler->filter('#fb-results')->each(function ($node) {
//            print $node->text()."\n";
//        });
    }
}
