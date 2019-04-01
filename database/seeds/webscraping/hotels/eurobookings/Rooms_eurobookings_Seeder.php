<?php

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

use Illuminate\Database\Seeder;

class Rooms_eurobookings_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $dA = [];

    public function mainRun($hotelURL, $dA)
    {
        $this->dA = $dA;
        //
        $this->dA['proxy'] = 'proxy.proxycrawl.com:9000';

        $this->dA['count_access_denied'] = 0;
        $this->dA['request_date'] = date("Y-m-d");
        Storage::makeDirectory('eurobookings/' . $this->dA['request_date']);

        try {
            $client = PhantomClient::getInstance();
            $client->getEngine()->setPath(base_path() . '/bin/phantomjs');
            $client->getEngine()->addOption('--load-images=false');
            $client->getEngine()->addOption('--ignore-ssl-errors=true');
            $client->getEngine()->addOption("--proxy=http://" . $this->dA['proxy']);
//                $client->getEngine()->addOption("--proxy=http://" . $this->dA['super_proxy'] . ":" . $this->dA['port'] . "");
//                $client->getEngine()->addOption("--proxy-auth=" . $this->dA['username'] . "-session-" . mt_rand() . ":" . $this->dA['password'] . "");
            $client->isLazy(); // Tells the client to wait for all resources before rendering
        } catch (\Exception $e) {

            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/phantomRequestError.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
        }

        try {
            $request = $client->getMessageFactory()->createRequest($hotelURL);
            $response = $client->getMessageFactory()->createResponse();
            $client->send($request, $response);
            $crawler = new Crawler($response->getContent());
        } catch (\Exception $e) {
            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/phantomRequestError2.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
        }

        try {
            if ($response->getStatus() == 200) {
                if ($crawler->filter('table#idEbAvailabilityRoomsTable > tbody')->count() > 0) {
                    $this->roomsData($crawler);
                    $this->dA['all_rooms'] = array_filter($this->dA['all_rooms']);

                    if (is_array($this->dA['all_rooms'])) {
                        $this->insertRoomDataIntoDB();
                    }
                }
            }
        } catch (\Exception $e) {
            Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/mainError.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
            print($e->getMessage());
        }
    }

    protected function insertRoomDataIntoDB()
    {
        foreach ($this->dA['all_rooms'] as $room) {

            if (!empty($room['room']) || !empty($room['price'])) {

                try {
                    $rid = $this->dA['request_date'] . $this->dA['check_in_date'] . $this->dA['check_out_date'] . $this->dA['hotel_name'] . $room['room'] . $room['price']; //Requestdate + CheckInDate + CheckOutDate + HotelId + RoomName + number of adults
                    $rid = str_replace(' ', '', $rid);
                    $rid = preg_replace('/\s+/u', '', $rid);
                    if (DB::table('rooms_prices_eurobookings')->where('rid', '=', $rid)->doesntExist()) {

                        DB::table('rooms_prices_eurobookings')->insert([
                            'uid' => uniqid(),
                            's_no' => 1,
                            'price' => $room['price'],
                            'currency' => $this->dA['currency'],
                            'room' => $room['room'],
                            'short_description' => (!empty($room['details']) ? serialize($room['details']) : null),
                            'facilities' => serialize($room['room_facilities']),
                            'photo' => $room['img'],
                            'hotel_uid' => $this->dA['hotel_uid'],
                            'hotel_eurobooking_id' => $this->dA['hotel_eurobooking_id'],
                            'hotel_name' => $this->dA['hotel_name'],
                            'number_of_adults_in_room_request' => $this->dA['adults'],
                            'check_in_date' => $this->dA['check_in_date'],
                            'check_out_date' => $this->dA['check_out_date'],
                            'rid' => $rid,
                            'request_date' => $this->dA['request_date'],
                            'source' => $this->dA['source'],
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()')
                        ]);
                        echo Carbon::now()->toDateTimeString() . ' Completed in-> ' . $this->dA['check_in_date'] . ' out-> ' . $this->dA['check_out_date'] . ' hotel-> ' . $this->dA['hotel_name'] . "\n";
                    } else {
                        echo Carbon::now()->toDateTimeString() . ' Existeddd in-> ' . $this->dA['check_in_date'] . ' out-> ' . $this->dA['check_out_date'] . ' hotel-> ' . $this->dA['hotel_name'] . "\n";
                    }
                } catch (\Exception $e) {
                    Storage::append('eurobookings/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/errorRoomsDB.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                    print($e->getMessage());
                }
            }
        }
        $this->dA['all_rooms'] = null;
    }

    protected function roomsData($crawler)
    {

        $crawler->filter('table#idEbAvailabilityRoomsTable > tbody')->each(function ($node) {
            $this->dA['temp']['room'] = '';
            $this->dA['temp']['node'] = $node;

            if ($node->filter('tr')->count() > 0) {
                $this->dA['all_rooms'] = $node->filter('tr')->each(function ($node1) {

                    if ($node1->filter('.clsRoomPhotoWrap > img')->count() > 0) {
                        $dr['img'] = $this->dA['temp']['img'] = str_replace('//', '', $node1->filter('.clsRoomPhotoWrap > img')->attr('src'));
                    }

                    if ($node1->filter('li.clsMoreRoomInfo')->count() > 0) {
                        $roomId = str_replace('idEbAvailability', '', $node1->filter('li.clsMoreRoomInfo')->attr('id'));
                        $dr['room_facilities'] = $this->dA['temp']['room_facilities'] = $this->dA['temp']['node']->filter('#' . strtolower($roomId) . ' > .clsEbAvailabilityRoomsBlockTextInner > p')->each(function ($node) {
                            return trim($node->text());
                        });
                    }

                    if ($node1->filter('.clsMoreRoomInfoTxt')->count() > 0) {
                        $dr['room'] = $this->dA['temp']['room'] = $node1->filter('.clsMoreRoomInfoTxt')->text();
                    } else {
                        $dr['room'] = null;
                    }

                    $dr['price'] = ($node1->filter('.clsSortByPrice')->count() > 0) ? $node1->filter('.clsSortByPrice')->text() : null;
                    if ($node1->filter('ul.clsUspList  > li')->count() > 0) {
                        $dr['details'] = $node1->filter('ul.clsUspList > li')->each(function ($node1) {
                            return $node1->text();
                        });
                    } else {
                        $dr['details'] = null;
                    }

                    if ((!empty($dr['details']) && !empty($dr['price'])) || empty($dr['room'])) {
                        $dr['room'] = (isset($this->dA['temp']['room']) ? $this->dA['temp']['room'] : null);

                    }
                    if (empty($dr['img'])) {
                        $dr['img'] = (isset($this->dA['temp']['img']) ? $this->dA['temp']['img'] : null);
                    }


                    if ($this->dA['temp']['room'] == $dr['room']) {
                        if (isset($this->dA['temp']['room_facilities'])) {
                            $dr['room_facilities'] = $this->dA['temp']['room_facilities'];
                        }
                    }

                    if (empty($dr['price'])) {
                        return null;
                    } else {
                        return $dr;
                    }
                });
            }
        });
    }

}
