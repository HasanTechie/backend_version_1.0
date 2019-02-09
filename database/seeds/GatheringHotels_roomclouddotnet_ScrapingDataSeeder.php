<?php

use Goutte\Client;

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


        $date = '2019-02-08';

        $end_date = '2020-02-28'; //last checkin date hogi last me


        $hotelArray = array
        (
            array(
                'id' => 341,
                'name' => 'Hotel Novecento',
                'address' => 'Via Carlo Emanuele I, 12, 00185 Roma RM, Italy',
                'city' => 'Rome',
                'phone' => '+39 06 70 96 247',
                'email' => 'info@novecentohotel.it',
                'website' => 'novecentohotel.it'

            )
        );


        if ($result1 = DB::table('rooms_prices_hotel_novecento')->orderBy('s_no', 'desc')->first()) {
            global $j;
            $j = $result1->s_no;
        } else {
            $j = 0;
        }

        while (strtotime($date) <= strtotime($end_date)) {

            foreach ($hotelArray as $hotels) {

                global $checkInDate, $checkOutDate, $hotels;

                $checkInDate = $date;

                $checkOutDate = date("Y-m-d", strtotime("+1 day", strtotime($date)));


                $url = "https://www.roomcloud.net/be/se1/hotel.jsp?hotel=" . $hotels['id'] . "&showCrossed=false&pin=&start_day=" . date("d", strtotime($checkInDate)) . "&start_month=" . date("m", strtotime($checkInDate)) . "&start_year=" . date("Y", strtotime($checkInDate)) . "&end_day=" . date("d", strtotime($checkOutDate)) . "&end_month=" . date("m", strtotime($checkOutDate)) . "&end_year=" . date("Y", strtotime($checkOutDate)) . "&adults=2&children=";

                sleep(1);

                $crawler = $client->request('GET', $url);

                try {

                    $crawler->filter('.room_div')->each(function ($node) {

                        $da['room'] = trim(str_replace(array("\r", "\n"), '', $node->filter('#room_description')->text()));
                        $da['room_description'] = trim(str_replace(array("\r", "\n"), '', $node->filter('div.modal-body')->text()));
                        $da['striked_price'] = trim(str_replace(array("\r", "\n"), '', $node->filter('span.offer')->text())); //strike price
                        $da['display_price'] = trim(str_replace(array("\r", "\n"), '', $node->filter('span.room_offer')->text())); //display

                        global $checkInDate, $checkOutDate, $hotels, $j;


                        $rid = 'currentdate' . date("Y-m-d") . 'checkin' . $checkInDate . 'checkout' . $checkOutDate . 'hotelid' . $hotels['id'] . $da['room']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName

                        dd('fix room name in rid');
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

                    });

                } catch (\Exception $e) {

                    echo 'InCompleted in->' . $checkInDate . 'out->' . $checkOutDate . ' hotel->' . $hotels['name'] . Carbon\Carbon::now()->toDateTimeString() . "\n";
                    echo $e->getMessage() . $e->getLine();
                }


            }

            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));

        }
    }
}
