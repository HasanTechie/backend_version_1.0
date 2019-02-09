<?php

use Goutte\Client;

use Illuminate\Database\Seeder;

class GatheringHotels_reservationsdotverticalbookingdotcom_ScrapingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();


        $date = '2019-03-08';

        $end_date = '2020-03-28'; //last checkin date hogi last me


        $hotelArray = array
        (
            array(
                'id' => 0000,
                'name' => 'Hotel PortaMaggiore',
                'address' => 'Piazza Di Porta Maggiore, 25 00185 ROMA',
                'city' => 'Rome',
                'phone' => '+39 06 7027927',
                'email' => 'info@shghotelportamaggiore.com',
                'website' => 'hotelportamaggiore.it'

            )
        );


//        if ($result1 = DB::table('rooms_prices_hotel_portamaggiore')->orderBy('s_no', 'desc')->first()) {
//            global $j;
//            $j = $result1->s_no;
//        } else {
//            $j = 0;
//        }

        while (strtotime($date) <= strtotime($end_date)) {

            foreach ($hotelArray as $hotels) {

                global $checkInDate, $checkOutDate, $hotels;

                $checkInDate = $date;

                $checkOutDate = date("Y-m-d", strtotime("+1 day", strtotime($date)));

                $url = "https://reservations.verticalbooking.com/reservations/risultato.html?tot_camere=1&tot_adulti=1&tot_bambini=0&gg=" . date("d", strtotime($checkInDate)) . "&mm=" . date("m", strtotime($checkInDate)) . "&aa=" . date("Y", strtotime($checkInDate)) . "&ggf=&mmf=&aaf=&notti_1=1&id_stile=12443&lingua_int=eng&id_albergo=15685&dc=3128&converti_valuta=&generic_codice=&countryCode=US&gps_latitude=&gps_longitude=&adulti1=1&bambini1=0";

                sleep(1);

                $crawler = $client->request('GET', $url);

                try {

                    $data = $crawler->filter('div.blocco_camera.room-box')->each(function ($node) {


                        $da['room'] = $node->filter('div.blocco_camera.room-box > div.descrizione_camera')->each(function ($node) {

                            $da['room'] = $node->filter('.titoletto')->text();
                            $da['room_description'] = $node->filter('div.descrizione_camera > p')->text();
                            $da['facilities'] = $node->filter('span.singolo-accessorio')->each(function ($node2) {
                                return trim($node2->text());
                            });
                            return $da;
                        });

                        $da['offer'] = $node->filter('div.blocco_camera.room-box > div.row > div.container_tariffa')->each(function ($node) {

                            $da['offer'] = $node->filter('.rate-details-content.blocco_tariffa')->each(function ($node) {
                                $da['offer_name'] = $node->filter('span.rate-name')->text();
                                $da['offer_details'] = $node->filter('p')->text();
                                $da['price'] = trim(str_replace(array("\r", "\n"), '', $node->filter('span.prezzo.titoletto.rate-price-daily')->text()));
                                return $da;
                            });
                            return $da;
                        });
                        return $da;
                    });

                    $removed = array_shift($data);

                    global $checkInDate, $checkOutDate, $hotels, $j;

                    foreach ($data as $instance){

                        dd($hotels);
//                        dd($instance);

                        $rid = 'currentdate' . date("Y-m-d") . 'checkin' . $checkInDate . 'checkout' . $checkOutDate . 'hotelname' . trim($hotels['name']) . $instance['room'][0]['room']; //Requestdate + CheckInDate + CheckOutDate + HotelId

                        dd($rid);
                        if (!(DB::table('rooms_prices_hotel_novecento')->where('rid', '=', $rid)->exists())) {
                            DB::table('rooms_prices_hotel_novecento')->insert([
                                'uid' => uniqid(),
                                's_no' => ++$j,
                                'display_price' => $da['display_price'],
                                'striked_price' => $da['striked_price'],
                                'room' => $da['room'],
                                'room_description' => $da['room_description'],
                                'hotel_id' => $hotels['id'],
                                'hotel_name' => $hotels['name'],
                                'hotel_address' => $hotels['address'],
                                'hotel_city' => $hotels['city'],
                                'hotel_phone' => $hotels['phone'],
                                'hotel_email' => $hotels['email'],
                                'hotel_website' => $hotels['website'],
                                'check_in_date' => $checkInDate,
                                'check_out_date' => $checkOutDate,
                                'rid' => $rid,
                                'source' => 'novecentohotel.it->roomcloud.net',
                                'requested_date' => date("Y-m-d"),
                                'created_at' => DB::raw('now()'),
                                'updated_at' => DB::raw('now()')
                            ]);
                            echo $j . ' ' . Carbon\Carbon::now()->toDateTimeString() . ' Completed in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $hotels['name'] . ' ' . $hotels['city'] . "\n";
                        } else {
                            echo $j . ' ' . Carbon\Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $hotels['name'] . ' ' . $hotels['city'] . "\n";
                        }
                    }

                } catch
                (\Exception $e) {

                    echo 'InCompleted in->' . $checkInDate . 'out->' . $checkOutDate . ' hotel->' . $hotels['name'] . Carbon\Carbon::now()->toDateTimeString() . "\n";
                    echo $e->getMessage() . $e->getLine();
                }


            }

            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));

        }
    }
}
