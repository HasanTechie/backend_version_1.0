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


        $date = '2019-03-03';

        $end_date = '2020-12-31'; //last checkin date hogi last me


        $hotelArray = array
        (
            array(
                'id_albergo' => 15685,
                'dc' => 3128,
                'name' => 'Hotel PortaMaggiore',
                'address' => 'Piazza di Porta Maggiore, 25, 00185 Roma RM, Italy',
                'city' => 'Rome',
                'phone' => '+39 06 7027927',
                'email' => 'info@shghotelportamaggiore.com',
                'website' => 'hotelportamaggiore.it'
            ),
            array(
                'id_albergo' => 16102,
                'dc' => 2391,
                'name' => 'SHG Hotel Antonella',
                'address' => 'Via Pontina, 00040 Pomezia RM, Italy',
                'city' => 'Pomezia',
                'phone' => '+39 06 911481',
                'email' => 'info@shghotelantonella.com',
                'website' => 'shghotelantonella.com'
            ),
            array(
                'id_albergo' => 16834,
                'dc' => 4753,
                'name' => 'SHG Hotel Villa Carlotta',
                'address' => 'Via Mazzini, 121, 28832 Belgirate VB, Italy',
                'city' => 'Belgirate',
                'phone' => '+39 0322 76461',
                'email' => 'info@shghotelvillacarlotta.com',
                'website' => 'shghotelvillacarlotta.com'
            ),
            array(
                'id_albergo' => 11338,
                'dc' => 333,
                'name' => 'SHG Hotel Salute Palace',
                'address' => 'Dorsoduro 222/a, 30123 Venice VE, Italy',
                'city' => 'Venice',
                'phone' => '+39 041 5235404',
                'email' => 'info@salutepalace.com',
                'website' => 'salutepalace.com'
            ),
            array(
                'id_albergo' => 5986,
                'dc' => 158,
                'name' => 'Villa Porro Pirelli',
                'address' => 'Via Tabacchi, 20, 21056 Induno Olona VA, Italy',
                'city' => 'Varese',
                'phone' => '+39 0332 840540',
                'email' => 'info@villaporropirelli.com',
                'website' => 'villaporropirelli.com'
            ),
            array(
                'id_albergo' => 11226,
                'dc' => 445,
                'name' => 'SHG Hotel de la Ville',
                'address' => 'Viale Verona, 12, 36100 Vicenza VI, Italy',
                'city' => 'Vicenza',
                'phone' => '+39 0444 549001',
                'email' => 'info@hoteldelavillevicenza.com',
                'website' => 'hoteldelavillevicenza.com'
            ),
            array(
                'id_albergo' => 14538,
                'dc' => 8856,
                'name' => 'SHG Hotel Catullo',
                'address' => 'Viale del Lavoro, 35, 37036 San Martino Buon Albergo VR, Italy',
                'city' => 'Verona',
                'phone' => '+39 045 99 50 00',
                'email' => 'info@shghotelcatullo.com',
                'website' => 'shghotelcatullo.com'
            ),
            array(
                'id_albergo' => 12104,
                'dc' => 8389,
                'name' => 'SHG Hotel Verona',
                'address' => 'Via Unità d\'Italia, 346, 37132 Verona VR, Italy',
                'city' => 'Verona',
                'phone' => '+39 045 895 2501',
                'email' => 'info@shghotelverona.com',
                'website' => 'shghotelverona.com'
            ),
            array(
                'id_albergo' => 12879,
                'dc' => 8759,
                'name' => 'SHG Grand Hotel Milano Malpensa',
                'address' => 'Via Lazzaretto, 1, 21019 Somma Lombardo VA, Italy',
                'city' => 'Somma Lombardo',
                'phone' => '+39 0331 951220',
                'email' => 'info@milanohotelmalpensa.com',
                'website' => 'grand-hotelmilanomalpensa.com'
            ),
            array(
                'id_albergo' => 17170,
                'dc' => 9129,
                'name' => 'Hotel Bologna',
                'address' => 'Via Risorgimento, 186, 40069 Zola Predosa BO, Italy',
                'city' => 'Zola Predosa',
                'phone' => '+39 051 751 101',
                'email' => 'info@shghotelbologna.com',
                'website' => 'shghotelbologna.com'
            ),
        );


        if ($result1 = DB::table('rooms_prices_chain_shg')->orderBy('s_no', 'desc')->first()) {
            global $j;
            $j = $result1->s_no;
        } else {
            $j = 0;
        }

        while (strtotime($date) <= strtotime($end_date)) {


            foreach ($hotelArray as $hotels) {

                for ($i = 1; $i < 5; $i++) {

                    $checkInDate = $date;

                    $checkOutDate = date("Y-m-d", strtotime("+1 day", strtotime($date)));

                    $url = "https://reservations.verticalbooking.com/reservations/risultato.html?tot_camere=1&tot_adulti=$i&tot_bambini=0&gg=" . date("d", strtotime($checkInDate)) . "&mm=" . date("m", strtotime($checkInDate)) . "&aa=" . date("Y", strtotime($checkInDate)) . "&ggf=&mmf=&aaf=&notti_1=1&id_stile=12443&lingua_int=eng&id_albergo=" . $hotels['id_albergo'] . "&dc=" . $hotels['dc'] . "&converti_valuta=&generic_codice=&countryCode=US&gps_latitude=&gps_longitude=&adulti1=1&bambini1=0";

                    sleep(1);

                    $crawler = $client->request('GET', $url);

                    try {

                        $roomsRawData = $crawler->filter('div.blocco_camera.room-box')->each(function ($node) {


                            try {
                                $da['room'] = $node->filter('div.blocco_camera.room-box > div.descrizione_camera')->each(function ($node) {

                                    $da['room'] = $node->filter('.titoletto')->text();
                                    $da['room_short_description'] = $node->filter('div.descrizione_camera > p')->text();
                                    $da['room_description'] = trim(str_replace(array("\r", "\n", "\t"), '', $node->filter('.container_dettagli')->text()));
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

                        dd($roomsRawData);


                        foreach ($roomsRawData as $instance) {

                            if (!empty($instance['room'])) {
                                $rid = 'currentdate' . date("Y-m-d") . 'checkin' . $checkInDate . 'checkout' . $checkOutDate . 'hotelname' . trim(str_replace(' ', '', $hotels['name'])) . 'room' . trim(str_replace(' ', '', $instance['room'][0]['room'])); //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName


                                if (!(DB::table('rooms_prices_chain_shg')->where('rid', '=', $rid)->exists())) {
                                    DB::table('rooms_prices_chain_shg')->insert([
                                        'uid' => uniqid(),
                                        's_no' => ++$j,
                                        'display_price' => $instance['offer'][0]['best_price'],
                                        'room' => $instance['room'][0]['room'],
                                        'room_short_description' => $instance['room'][0]['room_short_description'],
                                        'room_description' => $instance['room'][0]['room_description'],
                                        'room_facilities' => serialize($instance['room'][0]['facilities']),
                                        'room_rates_based_on_offers' => serialize($instance['offer'][0]['offer'][0]['offer']),
                                        'request' => '1 room for ' . $i . ' adults',
                                        'hotel_name' => $hotels['name'],
                                        'hotel_address' => $hotels['address'],
                                        'hotel_city' => $hotels['city'],
                                        'hotel_phone' => $hotels['phone'],
                                        'hotel_email' => $hotels['email'],
                                        'hotel_website' => $hotels['website'],
                                        'check_in_date' => $checkInDate,
                                        'check_out_date' => $checkOutDate,
                                        'rid' => $rid,
                                        'source' => 'reservations.verticalbooking.com',
                                        'requested_date' => date("Y-m-d"),
                                        'created_at' => DB::raw('now()'),
                                        'updated_at' => DB::raw('now()')
                                    ]);
                                    echo $j . ' ' . Carbon\Carbon::now()->toDateTimeString() . ' Completed in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $hotels['name'] . ', ' . $hotels['city'] . "\n";
                                } else {
                                    echo $j . ' ' . Carbon\Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $checkInDate . ' out-> ' . $checkOutDate . ' hotel-> ' . $hotels['name'] . ', ' . $hotels['city'] . "\n";
                                }
                            }
                        }

                    } catch
                    (\Exception $e) {

                        echo 'InCompleted in->' . $checkInDate . 'out->' . $checkOutDate . ' hotel->' . $hotels['name'] . Carbon\Carbon::now()->toDateTimeString() . "\n";
                        echo $e->getMessage() . $e->getLine();
                    }
                }
            }

            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));

        }
    }
}
