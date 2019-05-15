<?php

use JonnyW\PhantomJs\Client as PhantomClient;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class Rooms_hrs_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $dA = [];

    public function mainRun($dA)
    {
        try {
            $this->dA = $dA;

            $this->dA['proxy'] = 'proxy.proxycrawl.com:9000';
            $this->dA['timeOut'] = 8000;
            $this->dA['request_date'] = date("Y-m-d");
            $this->dA['count_!200'] = 0;
            $this->dA['count_!200b'] = 0;
            $this->dA['noFacilitiesFound'] = 0;
            $this->dA['count_noPriceFound'] = 0;
            $this->dA['full_break'] = false;

            if (!File::exists(storage_path() . '/app/hrs/' . $this->dA['request_date'] . '/')) {
                Storage::makeDirectory('hrs/' . $this->dA['request_date']);
            }

            restart2:
            $crawler = $this->phantomRequest($this->dA['request_url']);

            if ($crawler) {
                $this->roomData($crawler);

                try {
                    if (!empty($this->dA['all_rooms'])) {
                        if (is_array($this->dA['all_rooms'])) {
                            if (!empty($this->dA['room_facilities'])) {
                                $this->insertRoomsDataIntoDB();
                            } else {
                                if ($this->dA['noFacilitiesFound'] < 3) {
                                    $this->dA['noFacilitiesFound']++;
                                    goto restart2;
                                } else {
                                    Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/ignoreEmptyFacilities1a.log', 'url:' . $this->dA['request_url'] . ' ' . ';' . Carbon::now()->toDateTimeString() . "\n");
                                }
                            }
                        }
                    } else {
                        if ($this->dA['count_noPriceFound'] < 2) {
                            $this->dA['count_noPriceFound']++;
                            goto restart2;
                        } else {
                            Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/ignoreEmptyRoomOrPrice2b.log', 'url:' . $this->dA['request_url'] . ' ' . ';' . Carbon::now()->toDateTimeString() . "\n");
                        }
                    }
                    $this->dA['all_rooms'] = null;

                } catch (Exception $e) {
                    $this->catchException($e, 'ErrorDB');
                }
            }

        } catch (Exception $e) {
            $this->catchException($e, 'errorMain');
        }
    }

    protected function catchException($e, $fileName)
    {
        Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/' . $fileName . '.log', $e->getMessage() . ' ' . $e->getLine() . ' ' . Carbon::now()->toDateTimeString() . "\n");
        print($e->getMessage());
    }

    protected function insertRoomsDataIntoDB()
    {
        foreach ($this->dA['all_rooms'] as $rooms) {
            foreach ($rooms as $room) {
                if (!empty($room['room']) && !empty($room['price'])) {
                    $room['room_type'] = ($this->dA['adult'] > 1) ? 'doubleroom' : 'singleroom';

                    $rid = 'hrs' . $this->dA['hotel_hrs_id'] . $room['room'] . $room['room_type']
                        . $this->dA['adult'] . //HotelHRSId + RoomName + roomType + room Short D + criteria without numbers or currencies + number of adults + hrstag
                        substr(preg_replace('/[0-9.]+/', '', $room['criteria']), 0, 60) .
                        substr($room['room_short_description'], 0, 60);
                    $rid = substr(str_replace(' ', '', $rid), 0, 254);

                    $r = DB::table('rooms_hrs')->select('id')->where('rid', '=', $rid)->get();
                    if (count($r)) {
                        $r_id = $r[0]->id;
                    } else {
                        $r_id = DB::table('rooms_hrs')->insertGetId([
                            'room' => $room['room'],
                            'room_type' => $room['room_type'],
                            'criteria' => $room['criteria'],
                            'basic_conditions' => serialize($room['room_basic_conditions']),
                            'photo' => $room['room_image'],
                            'short_description' => $room['room_short_description'],
                            'facilities' => (isset($this->dA['room_facilities']) ? serialize($this->dA['room_facilities']) : null),
                            'hotel_id' => $this->dA['hotel_id'],
                            'rid' => $rid,
                            'created_at' => DB::raw('now()'),
                            'updated_at' => DB::raw('now()')
                        ]);
                    }

                    $room['price'] = $room['price'] . '.' . $room['cents'];
                    DB::table('prices_hrs')->insert([
                        'price' => $room['price'],
                        'currency' => $room['currency'],
                        'number_of_adults_in_room_request' => $this->dA['adult'],
                        'check_in_date' => $this->dA['check_in_date'],
                        'check_out_date' => $this->dA['check_out_date'],
                        'basic_conditions' => serialize($room['room_basic_conditions']),
                        'request_url' => $this->dA['request_url'],
                        'room_id' => $r_id,
                        'request_date' => $this->dA['request_date'],
                        'html_price' => $room['full_html_price'],
                        'created_at' => DB::raw('now()'),
                        'updated_at' => DB::raw('now()')
                    ]);
                    $this->dA['count_!200'] = 0;
                    $this->dA['count_!200b'] = 0;
                    $this->dA['noFacilitiesFound'] = 0;
                    $this->dA['count_noPriceFound'] = 0;

                }
            }
        }
    }

    protected function phantomRequest($url)
    {
        try {
            restart:
            $client = PhantomClient::getInstance();
            $client->getEngine()->setPath(base_path() . '/bin/phantomjs');
            $client->getEngine()->addOption('--load-images=false');
            $client->getEngine()->addOption('--ignore-ssl-errors=true');
//            $client->getEngine()->addOption("--proxy=http://" . $this->dA['proxy'][count($this->dA['proxy']) - 1]);
            $client->getEngine()->addOption("--proxy=http://" . $this->dA['proxy']);
            $client->isLazy(); // Tells the client to wait for all resources before rendering
            $request = $client->getMessageFactory()->createRequest($url);
            $request->setTimeout($this->dA['timeOut']);
            $response = $client->getMessageFactory()->createResponse();
            // Send the request
            $client->send($request, $response);
            $crawler = new Crawler($response->getContent());

            if ($response->getStatus() == 200) {
                return $crawler;
            } else {
                if ($this->dA['full_break'] == false) {
                    if ($this->dA['count_!200'] > 3) {
                        Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/BreakReasonA.log', 'url:' . $url . ' ;minor-break-reason4b:(getStatus())->' . $response->getStatus() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                        $this->dA['full_break'] = true;
                    } elseif ($this->dA['count_!200b'] > 20) {
                        Storage::append('hrs/' . $this->dA['request_date'] . '/' . $this->dA['city'] . '/BreakReasonB.log', 'url:' . $url . ' ;minor-break-reason4b:(getStatus())->' . $response->getStatus() . ' ' . Carbon::now()->toDateTimeString() . "\n");
                        $this->dA['full_break'] = true;
                    } else {
                        if ($response->getStatus() != 0 && $response->getStatus() != 408) {
                            $this->dA['count_!200']++;
                        } else {
                            $this->dA['count_!200b']++;
                        }
                        goto restart;
                    }
                }
            }
        } catch (Exception $e) {
            $this->catchException($e, 'phantomRequestError');
        }
    }

    protected function roomData($crawler)
    {
        if ($crawler->filter('div.jsAmenities.equipement.col33')->count() > 0) {

            $crawler->filter('div.jsAmenities.equipement.col33')->each(function ($node) {
                if ($node->filter('h5')->count() > 0) {
                    if ($node->filter('h5')->text() == 'Room facilities') {
                        $this->dA['room_facilities'] = ($node->filter('li')->count() > 0) ? $node->filter('li')->each(function ($node) {
                            return trim($node->text());
                        }) : null;
                    }
                }
            });
        }
        if ($crawler->filter('table#basket > tbody > tr')->count() > 0) {
            $this->dA['all_rooms'][] = $crawler->filter('table#basket > tbody > tr')->each(function ($node) {
                $dr['room'] = ($node->filter('td.roomOffer > div > h4')->count() > 0) ? $node->filter('td.roomOffer > div > h4')->text() : null;
                $dr['room_image'] = ($node->filter('td.roomOffer > div.imageWrap > img')->count() > 0) ? $node->filter('td.roomOffer > div.imageWrap > img')->attr('src') : null;
                $dr['room_basic_conditions'] = ($node->filter('td.roomOffer > div > ul.checkListSmall > li')->count() > 0) ? $node->filter('td.roomOffer > div > ul.checkListSmall > li')->each(function ($node) {
                    return ($node->count() > 0) ? $node->text() : null;
                }) : null;
                $dr['room_short_description'] = ($node->filter('td.roomOffer > div > p')->count() > 0) ? $node->filter('td.roomOffer > div > p')->text() : null;
//                                                $dr['price'] = ($node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tfoot > tr > td.price')->last()->text() : null;
//                                                $dr['price'] = ($node->filter('td.roomPrice > div > h4')->count() > 0) ? $node->filter('td.roomPrice > div > h4')->text() : null;
                $dr['full_text_price'] = ($node->filter('td.roomPrice > div > h4.price.standalonePrice')->count() > 0) ? $node->filter('td.roomPrice > div > h4.price.standalonePrice')->text() : null;
                $dr['full_html_price'] = ($node->filter('td.roomPrice > div > h4.price.standalonePrice')->count() > 0) ? $node->filter('td.roomPrice > div > h4.price.standalonePrice')->html() : null;
                $dr['cents'] = ($node->filter('td.roomPrice > div > h4.price.standalonePrice > sup')->count() > 0) ? $node->filter('td.roomPrice > div > h4.price.standalonePrice > sup')->text() : null;
                $dr['currency'] = str_replace(array(',', '.', ' '), '', preg_replace('/[0-9]+/', '', $dr['full_text_price']));
                $dr['price'] = preg_replace('/' . trim($dr['cents']) . '$/', '', preg_replace('/[^0-9.]/', '', str_replace(' ', '', $dr['full_text_price'])));

//                                                $dr['criteria'] = ($node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->count() > 0) ? $node->filter('td.roomPrice > div > div > table.data > tbody > tr > td > span')->last()->text() : null;
                $dr['criteria'] = ($node->filter('td.roomPrice > div > div.supplements')->count() > 0) ? $node->filter('td.roomPrice > div > div.supplements')->text() : null;
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
        }
    }
}

