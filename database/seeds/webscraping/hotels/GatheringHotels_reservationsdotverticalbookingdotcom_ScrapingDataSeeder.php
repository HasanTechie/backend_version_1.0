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


        $date = '2019-02-14';
//        $date = '2019-04-18';

        $end_date = '2020-12-31'; //last checkin date hogi last me


        $hotels = DB::table('hotels')->where([
            ['source', '=', 'reservations.verticalbooking.com'],
        ])->get();


        while (strtotime($date) <= strtotime($end_date)) {

            foreach ($hotels as $hotel) {

                for ($i = 1; $i < 5; $i++) {

                    $checkInDate = $date;

                    $checkOutDate = date("Y-m-d", strtotime("+1 day", strtotime($date)));

                    $url = "https://reservations.verticalbooking.com/reservations/risultato.html?tot_camere=1&tot_adulti=$i&tot_bambini=0&gg=" . date("d", strtotime($checkInDate)) . "&mm=" . date("m", strtotime($checkInDate)) . "&aa=" . date("Y", strtotime($checkInDate)) . "&ggf=&mmf=&aaf=&notti_1=1&id_stile=12443&lingua_int=eng&id_albergo=" . unserialize($hotel->all_data)['id_albergo'] . "&dc=" . unserialize($hotel->all_data)['dc'] . "&converti_valuta=&generic_codice=&countryCode=US&gps_latitude=&gps_longitude=&adulti1=1&bambini1=0";

                    sleep(1);

                    $crawler = $client->request('GET', $url);

                    try {
                        $roomsRawData = null;
                        $roomsRawData = $crawler->filter('div.blocco_camera.room-box')->each(function ($node) {


                            try {
                                $da['room'] = $node->filter('div.blocco_camera.room-box > div.descrizione_camera')->each(function ($node) {

                                    $da['room'] = ($node->filter('.titoletto')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $node->filter('.titoletto')->text())) : null;
                                    $da['room_short_description'] = ($node->filter('div.descrizione_camera > p')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $node->filter('div.descrizione_camera > p')->text())) : null;
                                    $da['room_description'] = ($node->filter('.container_dettagli')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $node->filter('.container_dettagli')->text())) : null;
                                    $da['facilities'] = array_filter($node->filter('span.singolo-accessorio')->each(function ($node2) {
                                        return trim($node2->text());
                                    }));
                                    return $da;
                                });

                                $da['offer'] = $node->filter('div.blocco_camera.room-box > div.row')->each(function ($node) {

                                    $da['best_price'] = trim(str_replace('EUR', '', $node->filter('strong')->text()));
                                    $da['offer'] = $node->filter('div.container_tariffa')->each(function ($node) {
                                        $da['offer'] = $node->filter('.rate-details-content.blocco_tariffa')->each(function ($node) {
                                            $da['offer_name'] = $node->filter('span.rate-name')->text();
                                            $da['offer_details'] = $node->filter('p')->text();
                                            $da['striked_price'] = ($node->filter('span.offical-price')->count() > 0) ? trim(str_replace(array("\r", "\n", "\t"), '', $node->filter('span.offical-price')->text())) : null; //striked price
                                            $da['price'] = trim(str_replace(array("\r", "\n", "\t"), '', $node->filter('span.prezzo.titoletto.rate-price-daily')->text()));
                                            return $da;
                                        });
                                        return $da;
                                    });
                                    return $da;

                                });
                                return $da;
                            } catch (\Exception $e) {
                                return $e;
                            }
                        });


                        foreach ($roomsRawData as $instance) {


                            if (!empty($instance['room'])) {
                                $rid = 'currentdate' . date("Y-m-d") . 'checkin' . $checkInDate . 'checkout' . $checkOutDate . 'hotelname' . trim(str_replace(' ', '', $hotel->name)) . 'room' . trim(str_replace(' ', '', $instance['room'][0]['room'])) . $i; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults

                                if ($result1 = DB::table('rooms_prices_vertical_booking')->orderBy('s_no', 'desc')->first()) {
                                    global $j;
                                    $j = $result1->s_no;
                                } else {
                                    $j = 0;
                                }


                                if (!(DB::table('rooms_prices_vertical_booking')->where('rid', '=', $rid)->exists())) {
                                    DB::table('rooms_prices_vertical_booking')->insert([
                                        'uid' => uniqid(),
                                        's_no' => ++$j,
                                        'display_price' => $instance['offer'][0]['best_price'],
                                        'room' => $instance['room'][0]['room'],
                                        'room_short_description' => $instance['room'][0]['room_short_description'],
                                        'room_description' => $instance['room'][0]['room_description'],
                                        'room_facilities' => serialize($instance['room'][0]['facilities']),
                                        'room_rates_based_on_offers' => serialize($instance['offer'][0]['offer'][0]['offer']),
                                        'number_of_adults_in_room_request' => $i,
                                        'hotel_uid' => $hotel->uid,
                                        'hotel_name' => $hotel->name,
                                        'hotel_address' => $hotel->address,
                                        'hotel_city' => $hotel->city,
                                        'hotel_phone' => $hotel->phone,
                                        'hotel_website' => $hotel->website,
                                        'hotel_email' => unserialize($hotel->all_data)['email'],
                                        'chain_website' => (isset(unserialize($hotel->all_data)['chain']) ? unserialize($hotel->all_data)['chain'] : null),
                                        'check_in_date' => $checkInDate,
                                        'check_out_date' => $checkOutDate,
                                        'rid' => $rid,
                                        'source' => 'reservations.verticalbooking.com',
                                        'request_date' => date("Y-m-d"),
                                        'created_at' => DB::raw('now()'),
                                        'updated_at' => DB::raw('now()')
                                    ]);
                                    echo $j . ' ' . $i . ' ' . Carbon\Carbon::now()->toDateTimeString() . ' Completed in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $hotel->name . ', ' . $hotel->city . "\n";
                                } else {
                                    echo $j . ' ' . $i . ' ' . Carbon\Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $hotel->name . ', ' . $hotel->city . "\n";
                                }
                            }
                        }


                    } catch
                    (\Exception $e) {

                        echo 'InCompleted in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $hotel->name . Carbon\Carbon::now()->toDateTimeString() . "\n";
                        echo $e->getMessage() . $e->getLine();
                    }

                }
            }

            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));

        }
    }
}
