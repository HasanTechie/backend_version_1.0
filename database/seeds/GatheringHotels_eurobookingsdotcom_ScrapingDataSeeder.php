<?php

use Goutte\Client;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Seeder;

class GatheringHotels_eurobookingsdotcom_ScrapingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        session_start();
        $_SESSION['room'] = '';
        $url = "https://www.eurobookings.com/search.html?q=start:2019-02-18;end:2019-02-19;rmcnf:1[2,0];frm:9;sort:0_desc;cur:USD;dsti:536;dstt:1;dsts:Berlin;fac:0;stars:;rad:0;wa:0;offset:1;sf:1;";

//        echo $url ."\n";

        $client = new Client();

        $crawler = $client->request('GET', $url);

        $data = $crawler->filter('.clsHotelNameSearchResults')->each(function ($node) {

            $client = new Client();
            $crawler = $client->request('GET', $node->attr('href'));;
//            $hotel = $crawler->filter('#idEbAvailabilityRoomsTable')->each(function ($node) {

//                $da['rooms_prices'] = $node->filter('tr')->each(function ($node1) {
//
//
//                    if ($node1->filter('.clsMoreRoomInfoTxt')->count() > 0) {
//                        $da['name'] = $_SESSION['room'] = $node1->filter('.clsMoreRoomInfoTxt')->text();
//                    } else {
//                        $da['name'] = null;
//                    }
//
//                    $da['price'] = ($node1->filter('.clsSortByPrice')->count() > 0) ? $node1->filter('.clsSortByPrice')->text() : null;
//                    $da['details'] = ($node1->filter('.clsUspList')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $node1->filter('.clsUspList')->text())) : null;
//
//                    if ($da['details'] != null && !empty($da['price'])) {
//                        $da['name'] = (isset($_SESSION['room']) ? $_SESSION['room'] : null);
//                    }
//
//                    if (!empty($da['price']) && empty($da['details'])) {
//                        $da['details'] = 'Normal';
//                    }
//
//                    return $da;
//                });

//                foreach ($da as $key => $value) {
//                    if (empty($value['price'])) {
//
//                        unset($da[$key]);
//                    }
//                }
//
//                dd($da);
//
//                return $da;
//            });


            $da['hotel_info'] = $crawler->filter('#idEbFirstBlockUnderTab')->html();

            Storage::put('myfile.html', $da['hotel_info']);

            dd($da);

//            return $hotel;
        });
        dd($data);

//        dd($crawler->html());
    }
}
