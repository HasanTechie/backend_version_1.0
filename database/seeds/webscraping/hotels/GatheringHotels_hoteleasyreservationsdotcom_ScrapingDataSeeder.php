<?php

use Goutte\Client;

use Illuminate\Database\Seeder;

class GatheringHotels_hoteleasyreservationsdotcom_ScrapingDataSeeder extends Seeder
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

        $_SESSION["i"] = 0;
        $_SESSION["j"] = 0;

        $client = new Client();

        $url = "https://www.hoteleasyreservations.com/her5th/(S(mcfpq5c3rmgengjb51qyusu2))/her/pren1.aspx?_ga=&EASYCHATSESSION=";

        $crawler = $client->request('GET', $url);

        global $dataArray;
        $dataArray = [];

        $j = 0;

        global $roomArray;
        global $descriptionArray;
        global $facilitiesArray;

        $crawler->filter("[name='bloccoCamera_1']")->each(function ($node) {

            global $dataArray;


            $node->filter('.SelezioneCamereServiziElenco')->each(function ($node1) {


                global $facilitiesArray;

                $facilitiesArray[] = $node1->filter('.SelezioneCamereServiziElencoVoci')->each(function ($node2) {
                    return trim($node2->text());
                });


            });


            $node->filter('.testoTitoloCamera')->each(function ($node1) {
                global $roomArray;
                $roomArray[] = trim($node1->filter(".testoTitoloCamera")->text());

            });

            $node->filter('.SelezioneCamereDescrizione > span:nth-child(4)')->each(function ($node1) {
                global $descriptionArray;

                $descriptionArray[] = trim($node1->text());

            });


            global $roomArray;
            global $descriptionArray;
            global $facilitiesArray;
            $_SESSION['room'] = $roomArray;
            $_SESSION['description'] = $descriptionArray;
            $_SESSION['facilities'] = $facilitiesArray;


            $node->filter('.cellflex')->each(function ($node2) {

                global $dataArray;

                $dataArray['day'] = trim($node2->filter('.weekDayFlex')->text());
                $dataArray['month'] = trim($node2->filter('.ddmonthFlex')->text());
                if ($_SESSION["i"] == 21) {

                    if ($_SESSION["j"] < count($_SESSION['room'])) {

                        $_SESSION["j"]++;
                    }
                    $_SESSION["i"] = 0;

                }

                $dataArray['room'] = $_SESSION['room'][$_SESSION["j"]];
                $dataArray['description'] = $_SESSION['description'][$_SESSION["j"]];
                $dataArray['facilities'] = $_SESSION['facilities'][$_SESSION["j"]];

                $dataArray['price'] = trim($node2->filter('#spanPriceFlex' . $_SESSION["i"]++)->text());

                dd($dataArray);

                global $j;

                $rid = preg_replace('/\s+/', '', $dataArray['month']) . preg_replace('/\s+/', '', $dataArray['price']) . preg_replace('/\s+/', '', $dataArray['room']) . preg_replace('/\s+/', '', $dataArray['day']);

                /*
                if (!(DB::table('rooms')->where('rid', '=', $rid)->exists())) {
                    DB::table('rooms')->insert([
                        'uid' => uniqid(),
                        's_no' => ++$j,
                        'price' => $dataArray['price'],
                        'month' => $dataArray['month'],
                        'day' => $dataArray['day'],
                        'room' => $dataArray['room'],
                        'description' => $dataArray['description'],
                        'facilities' => serialize($dataArray['facilities']),
                        'hotel_name' => 'Hotel Emona Aquaeductus',
                        'hotel_address' => 'Via Statilia, 23 | 00185 Rome, Italy',
                        'hotel_phone' => '0039 06 7027827',
                        'hotel_email' => 'info@hotelaquaeductus.it',
                        'hotel_website' => 'hotelaquaeductus.it',
                        'rid' => $rid,
                        'source' => 'hoteleasyreservations.com',
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                    echo 'Completed ' . $dataArray['month'] . "\n";
                } else {
                    echo 'Exisitedd ' . $dataArray['month'] . "\n";
                }
                */

            });
        });


//        $crawler->filter('.flexible-row > .cellflex')->each(function ($node,$i=0) {
//        $crawler->filter('body')->each(function ($node, $i = 0) {
//
//            $node->filter('.testoTitoloCamera')->each(function ($node1, $i = 0) {
////                $dataArray['name'] = $node1->text();
//
//                print $node1->text() . "\n";
//
//            });
//
//            $node->filter('.cellflex')->each(function ($node2, $i = 0) {
//
////                $dataArray['day'] = $node2->filter('.weekDayFlex')->text();
////                $dataArray['month'] = $node2->filter('.ddmonthFlex')->text();
////                $dataArray['price'] = $node2->filter('#spanPriceFlex0')->text();
////                print $node2->filter('.weekDayFlex')->text() . "\n";
////                print $node2->filter('.ddmonthFlex')->text() . "\n";
////                print $node2->filter('#spanPriceFlex0')->text() . "\n";
//                print $node2->text() . "\n";
//
////                dd($dataArray);
//            });
//        });
//

//        $crawler->filter(".bloccoCamera.hvr-grow-shadow")->each(function ($node, $i = 0) {
//        $crawler->filter("[name='bloccoCamera_1']")->each(function ($node) {
//
//            global $dataArray;
//            $dataArray['room'] = $node->filter(".testoTitoloCamera")->text() . "\n";
//            print $node->filter(".testoTitoloCamera")->text() . "\n";
//
//            $node->filter('.cellflex')->each(function ($node2, $i = 0) {
//
//                global $dataArray;
//////
//                $dataArray['day'] = $node2->filter('.weekDayFlex')->text();
//                $dataArray['month'] = $node2->filter('.ddmonthFlex')->text();
//                if ($_SESSION["i"] == 21) {
//                    $_SESSION["i"] = 0;
//                }
//                $dataArray['price'] = $node2->filter('#spanPriceFlex' . $_SESSION["i"]++)->text();


//                if (!empty($node2->filter('.weekDayFlex'))) {
//
//                    print $node2->filter('.weekDayFlex')->text() . "\n";
//                    print $node2->filter('.ddmonthFlex')->text() . "\n";
//                    print $node2->filter('#spanPriceFlex0')->text() . "\n";
//                }
//                print $node2->filter('.weekDayFlex')->text() . "\n";
//                print $node2->filter('.ddmonthFlex')->text() . "\n";

//                $i = 0;
//                if ($i > 21) {
//                    $i = 0;
//                } else {
//                    if ($i != 0) {
//                        $i++;
//                    }
//                }


//                if ($_SESSION["i"] == 21) {
//                    $_SESSION["i"] = 0;
//                }
//                print $node2->filter("#spanPriceFlex" . $_SESSION["i"]++)->text() . "\n";


//
//                dd();
//                dd($dataArray);

//                print_r($dataArray);
//            });
//
//        });


//        $crawler->filter("[name='bloccoCamera_1']")->each(function ($node, $i = 0) {
//
//            dd($node);
//        });

//        $crawler->filter('.cellflex')->each(function ($node, $i = 0) {
//            print $node->text() . "\n";
//
//            $day = $node->filter('.weekDayFlex')->text();
//            $month = $node->filter('.ddmonthFlex')->text();
//            $price = $node->filter('#spanPriceFlex0')->text();
//            $room = $node->filter('.testoTitoloCamera')->text();
//
//
//        });


//        dd($crawler);


    }
}
