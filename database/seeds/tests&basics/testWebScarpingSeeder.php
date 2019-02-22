<?php

use Goutte\Client as GoutteClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;

use Illuminate\Database\Seeder;

class testWebScarpingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = "https://www.hrs.com/en/hotel/berlin/d-55133/2#container=&locationId=55133&requestUrl=%2Fen%2Fhotel%2Fberlin%2Fd-55133&showAlternates=false&toggle=&arrival=2019-02-28&departure=2019-03-01&lang=en&minPrice=false&roomType=double&singleRoomCount=0&doubleRoomCount=1&_=1550832580038";

        $client = new GoutteClient();
        $crawler = $client->request('GET', $url);
        echo $url;
        $data = $crawler->filter('a.sw-hotel-list__link')->each(function ($node) {
            $da['link'] = $node->attr('href');

            $da['hotel_id'] = preg_replace('/[^0-9]/', '', $da['link']);

            $adult=1;
            $url1 = "https://www.hrs.com/hotelData.do?hotelnumber=" . $da['hotel_id'] . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=EUR&startDateDay=22&startDateMonth=02&startDateYear=2019&endDateDay=23&endDateMonth=02&endDateYear=2019&adults=$adult&singleRooms=1&doubleRooms=0&children=0#priceAnchor";
            $adults=2;
            $url2 = "https://www.hrs.com/hotelData.do?hotelnumber=" . $da['hotel_id'] . "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=EUR&startDateDay=22&startDateMonth=02&startDateYear=2019&endDateDay=23&endDateMonth=02&endDateYear=2019&adults=$adults&singleRooms=0&doubleRooms=1&children=0#priceAnchor";

            $client = new GoutteClient();
            $crawler = $client->request('GET', $url1);
            $crawler2 = $client->request('GET', $url2);

            $da['all_double_rooms'] = $crawler2->filter('table#basket > tbody > tr ')->each(function ($node) {
                $dr['room'] = ($node->filter('td.roomOffer > div > h4')->count() > 0) ? $node->filter('td.roomOffer > div > h4')->text() : null;
                $dr['room_type'] = ($node->count() > 0) ? $node->attr('data-roomtype') : null;
                $dr['details'] = ($node->filter('td.roomOffer > div > p')->count() > 0) ? $node->filter('td.roomOffer > div > p')->text() : null;
                $dr['price'] = ($node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->last()->text() : null;
                $dr['included'] = ($node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->last()->text() : null;
                foreach ($dr as $key => $value) {
                    if (!is_array($value)) {
                        $dr[$key] = trim(str_replace(array("\r", "\n", "\t"), '', $value));
                    }
                    if (empty($value)) {
                        unset($dr[$key]);
                    }
                }
                return $dr;
            });

            $da['all_single_rooms'] = $crawler->filter('table#basket > tbody > tr ')->each(function ($node) {
                $dr['room'] = ($node->filter('td.roomOffer > div > h4')->count() > 0) ? $node->filter('td.roomOffer > div > h4')->text() : null;
                $dr['room_type'] = ($node->count() > 0) ? $node->attr('data-roomtype') : null;
                $dr['details'] = ($node->filter('td.roomOffer > div > p')->count() > 0) ? $node->filter('td.roomOffer > div > p')->text() : null;
                $dr['price'] = ($node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->last()->text() : null;
                $dr['criteria'] = ($node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->last()->text() : null;
                foreach ($dr as $key => $value) {
                    if (!is_array($value)) {
                        $dr[$key] = trim(str_replace(array("\r", "\n", "\t"), '', $value));
                    }
                    if (empty($value)) {
                        unset($dr[$key]);
                    }
                }
                return $dr;
            });

            $da['hotel_name'] = ($crawler->filter('div#detailsHead > h2 > span.title')->count() > 0) ? $crawler->filter('div#detailsHead > h2 > span.title')->text() : null;
            $da['hotel_address'] = ($crawler->filter('address.hotelAdress')->count() > 0) ? $crawler->filter('address.hotelAdress')->text() : null;

            return $da;
        });


    }
}
/*        $client = new Client();
        $crawler = $client->request('GET', 'https://global.momondo.com/hotel-search/Berlin,Germany-c9109/2019-02-23/2019-02-24/2adults?fs=price-options=onlinereq&sort=rank_a');
//        $link = $crawler->selectLink('Hotel Policies')->link();
//        $crawler = $client->click($link);

        Storage::put('hotel_details.html', $crawler->html());

        $crawler->filter('h2 > a')->each(function ($node) {
            print $node->text()."\n";
        });*/
/*
        $url = "https://www.hotel-bb.com/en/booking-hotel-bb-france.htm?txt-search=Tuscany&type=localization-region&arrive=11%2F03%2F2019&pars=12%2F03%2F2019&selectPersonNumber=2&search=";
        $url = "https://www.hotel-bb.es/en/hotel/barcelona-granollers?original_action=%2Fen%2Fhotel%2Fbarcelona-granollers&gps%5Bvalue%5D=41.6140729%2C2.3038705&gps%5Bdistance%5D%5Bfrom%5D=10&arrival_date=03%2F11%2F2019&departure_date=03%2F12%2F2019&destination=Barcelona%20Granollers&internal_keywords=Barcelona%20Granollers&rooms_number=1&adults_number=1&children_number=0&currency_code=EUR";

        echo $url . "\n";
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $data = $crawler->filter('.price-info')->each(function ($node) {
            return $node->text();
        });

        dd($data);
        */
