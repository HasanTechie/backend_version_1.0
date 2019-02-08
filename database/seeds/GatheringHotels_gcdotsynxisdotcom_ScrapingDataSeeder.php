<?php

use Goutte\Client;

use Illuminate\Database\Seeder;

class GatheringHotels_gcdotsynxisdotcom_ScrapingDataSeeder extends Seeder
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

        session_start();
        $_SESSION['displayPrice'] = 0;

        $date = '2020-01-07';

        $end_date = '2020-02-10'; //last checkin date hogi last me


        $hotelArray = array
        (
            array(
                'chain_id' => 12036,
                'id' => 50977,
                'name' => 'BAGLIONI HOTEL LUNA',
                'city' => 'Venice',
                'phone' => '+39 04 18520568',
                'email' => 'reservations.lunavenezia@baglionihotels.com',
                'website' => 'baglionihotels.com'

            ),

            array(
                'chain_id' => 12036,
                'id' => 50976,
                'name' => 'BAGLIONI HOTEL CARLTON',
                'city' => 'Milan',
                'phone' => '+39 02 82955178',
                'email' => 'reservations.carltonmilano@baglionihotels.com',
                'website' => 'baglionihotels.com'
            ),

            array(
                'chain_id' => 12036,
                'id' => 50970,
                'name' => 'RELAIS SANTA CROCE',
                'city' => 'Florence',
                'phone' => '+39 0550 622004',
                'email' => 'reservations.santacrocefirenze@baglionihotels.com',
                'website' => 'baglionihotels.com'

            ),

            array(
                'chain_id' => 12036,
                'id' => 50978,
                'name' => 'BAGLIONI HOTEL REGINA',
                'city' => 'Rome',
                'phone' => '+39 0694 501054',
                'email' => 'reservations.reginaroma@baglionihotels.com',
                'website' => 'baglionihotels.com'
            ),

            array(
                'chain_id' => 12036,
                'id' => 50975,
                'name' => 'BAGLIONI HOTEL LONDON',
                'city' => 'London',
                'phone' => '+44 (0) 2038730232',
                'email' => 'reservations.london@baglionihotels.com',
                'website' => 'baglionihotels.com'
            ),

            array(
                'chain_id' => 12036,
                'id' => 71811,
                'name' => 'BAGLIONI RESORT MALDIVES',
                'city' => 'Maldives',
                'phone' => null,
                'email' => null,
                'website' => 'baglionihotels.com'
            ),

            array(
                'chain_id' => 12036,
                'id' => 61190,
                'name' => 'BAGLIONI RESORT CALA DEL PORTO',
                'city' => 'Tuscany',
                'phone' => '+39 0564 1900416',
                'email' => 'reservations.calapuntaala@baglionihotels.com',
                'website' => 'baglionihotels.com'
            ),


        );


        if ($result1 = DB::table('rooms_prices_hotel_baglioni')->orderBy('s_no', 'desc')->first()) {
            global $j;
            $j = $result1->s_no;
        } else {
            $j = 0;
        }

        $hotels = $checkInDate = $checkOutDate = null;

        while (strtotime($date) <= strtotime($end_date)) {

            foreach ($hotelArray as $hotels1) {

                global $hotels, $checkInDate, $checkOutDate;

                $checkInDate = $date;
                $hotels = $hotels1;
                $checkOutDate = date("Y-m-d", strtotime("+1 day", strtotime($date)));

                $url = "https://gc.synxis.com/rez.aspx?Chain=" . $hotels['chain_id'] . "&Hotel=" . $hotels['id'] . "&Shell=RBE&Template=RBE&arrive=$checkInDate&depart=$checkOutDate&adult=1&rooms=&promo=&start=availresults&locale=en-US";

                $crawler = $client->request('GET', $url);

//                global $checkOutDate, $checkInDate, $hotels, $j;

                try {

                    sleep(1);


                    $crawler->filter('.ProductsInCategory.Bg3.Br2.Mrgn1.Pdng10')->each(function ($node) {
                        $_SESSION['displayPrice'] = 0;

                        $da['rate_type'] = $node->filter('.Bg6.Br1')->each(function ($node1) {

                            $da['striked_price'] = ($node1->filter('span.PromoOriginalPrice.StrikeOut.TxtLt.tLight')->count() > 0) ? str_replace(' ', '', trim(str_replace(array("\r", "\n"), '', $node1->filter('span.PromoOriginalPrice.StrikeOut.TxtLt.tLight')->text()))) : null; //striked price with currCode
                            $da['display_price'] = ($node1->filter('div.ProductPriceGroup> span:nth-child(3)')->count() > 0) ? $node1->filter('div.ProductPriceGroup> span:nth-child(3)')->text() : null; //display price

                            $da['currency'] = ($node1->filter('span.CurrCode.tBold.tSmall')->count() > 0) ? $node1->filter('span.CurrCode.tBold.tSmall')->text() : null; //Currency
                            $da['pernight'] = ($node1->filter('span.PriceFreq.tSmall.tLight')->count() > 0) ? $node1->filter('span.PriceFreq.tSmall.tLight')->text() : null; //pernight
                            $da['room_type'] = $_SESSION['room_type'] = ($node1->filter('span.PriceInfoValue.Pdng5')->count() > 0) ? $node1->filter('span.PriceInfoValue.Pdng5')->text() : null; //room_type
                            $da['rate_type'] = ($node1->filter('span.RateName')->count() > 0) ? $node1->filter('span.RateName')->text() : null; //rate_type
                            $da['value_before_tax'] = ($node1->filter('td.PDtlTTValue.PDtlRmTl.Bg1.Br2.Pdng5 > span:first-child')->count() > 0) ? $node1->filter('td.PDtlTTValue.PDtlRmTl.Bg1.Br2.Pdng5 > span:first-child')->text() : null; //value_before_tax
                            $da['tax'] = ($node1->filter('td.PDtlTTValue.PDtlTax.Bg1.Br2.Pdng5 > span:first-child')->count() > 0) ? $node1->filter('td.PDtlTTValue.PDtlTax.Bg1.Br2.Pdng5 > span:first-child')->text() : null; //tax
                            $da['total_including_tax'] = ($node1->filter('td.PDtlTTValue.PDtlTotal.Bg2.Br2.Pdng5 > .hSize4.tBold')->count() > 0) ? $node1->filter('td.PDtlTTValue.PDtlTotal.Bg2.Br2.Pdng5 > .hSize4.tBold')->text() : null; //total_including_tax
                            $da['date_from_field'] = ($node1->filter('span.PerDayDayLabel')->count() > 0) ? $node1->filter('span.PerDayDayLabel')->text() : null; //date_from_field
                            $da['short_description'] = ($node1->filter('.ProductShortDesc')->count() > 0) ? trim(str_replace(array("\r", "\n"), '', $node1->filter('.ProductShortDesc')->text())) : null; //short description
                            $da['description'] = ($node1->filter('.ProductLongDesc.Mrgn6')->count() > 0) ? trim(str_replace(array("\r", "\n"), '', $node1->filter('.ProductLongDesc.Mrgn6')->text())) : null; //description


                            if (!empty($da['display_price'])) {
                                if ($_SESSION['displayPrice'] == 0) {
                                    $_SESSION['displayPrice'] = $da['display_price'];
                                }
                                if ($_SESSION['displayPrice'] > $da['display_price']) {
                                    $_SESSION['displayPrice'] = $da['display_price'];
                                }
                            }

                            if (empty($da['display_price'])) {
                                if ($_SESSION['displayPrice'] == 0) {
                                    $_SESSION['displayPrice'] = $da['total_including_tax'];
                                }
                            }

                            return $da;
                        });


                        global $checkOutDate, $checkInDate, $hotels, $j;

                        $rid = 'currentdate' . date("Y-m-d") . 'checkin' . $checkInDate . 'checkout' . $checkOutDate . 'hotelid' . $hotels['id'] . $_SESSION['room_type']; //Requestdate + CheckInDate + CheckOutDate + HotelId

                        if (!(DB::table('rooms_prices_hotel_baglioni')->where('rid', '=', $rid)->exists())) {
                            DB::table('rooms_prices_hotel_baglioni')->insert([
                                'uid' => uniqid(),
                                's_no' => ++$j,
                                'lowest_price' => $_SESSION['displayPrice'],
                                'room' => $_SESSION['room_type'],
                                'room_all_rates_and_details' => serialize($da['rate_type']),
                                'hotel_id' => $hotels['id'],
                                'hotel_name' => $hotels['name'],
                                'hotel_city' => $hotels['city'],
                                'hotel_phone' => $hotels['phone'],
                                'hotel_email' => $hotels['email'],
                                'hotel_website' => $hotels['website'],
                                'check_in_date' => $checkInDate,
                                'check_out_date' => $checkOutDate,
                                'rid' => $rid,
                                'source' => 'baglionihotels.com->gc.synxis.com',
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

//                $crawler->filter('.ProductsHeader.Br2.Mrgn1.Pdng4')->each(function ($node) {
//
//
////                    $node->click('View Rates')->link();
//
//
//
////                    $node->filter('.hSize2')->each(function ($node2) {
////                        print $node2->text() . " ";
////                    });
////                    $node->filter('.Price.tBold.hSize1')->each(function ($node1) {
////                    });
//                    $node->filter('.Bg6.Br1')->each(function ($node1) {
//                        print $node1->text() . "\n";
//                    });
//                });
