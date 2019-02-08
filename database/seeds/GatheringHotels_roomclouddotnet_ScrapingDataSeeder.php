<?php

use Illuminate\Database\Seeder;

class GatheringHotels_roomclouddotnet_ScrapingDataSeeder extends Seeder
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

        $url = "https://www.roomcloud.net/be/se1/hotel.jsp?hotel=341&showCrossed=false&pin=&start_day=11&start_month=03&start_year=2019&end_day=12&end_month=03&end_year=2019&adults=2&children=";

        $crawler = $client->request('GET', $url);

//        $data =$crawler->filter('room_div')->text();
//        dd($data);

        $data =$crawler->filter('.room_div')->each(function ($node) {

            $da[] = trim(str_replace(array("\r", "\n"), '', $node->filter('#room_description')->text()));
            $da[] = trim(str_replace(array("\r", "\n"), '', $node->filter('div.modal-body')->text()));
            $da[] = trim(str_replace(array("\r", "\n"), '', $node->filter('span.offer')->text())); //strike price
            $da[] = trim(str_replace(array("\r", "\n"), '', $node->filter('span.room_offer')->text())); //display
            return $da;
        });

        dd($data);
    }
}
