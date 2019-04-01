<?php

use App\Jobs\GatherRoomsDataJob;

use Illuminate\Database\Seeder;

class Rooms_Queues_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $i = 0;
        $dA = [];
        $dA['currency'] = 'EUR';
        $dA['adults'] = 2;
        $dA['start_date'] = '2019-04-12';
        $dA['end_date'] = '2019-05-12';
        $dA['source'] = 'eurobookings.com';

        $hotels = DB::table('hotels_eurobookings_data')->inRandomOrder()->get();

        while (strtotime($dA['start_date']) <= strtotime($dA['end_date'])) {
            $dA['check_in_date'] = $dA['start_date'];
            $dA['check_out_date'] = date("Y-m-d", strtotime("+1 day", strtotime($dA['start_date'])));
            foreach ($hotels as $hotel) {
                $hotelBasicURL = explode('?', $hotel->hotel_url_on_eurobookings);
                $hotelURL = $hotelBasicURL[0] . "?q=start:" . $dA['check_in_date'] . ";end:" . $dA['check_out_date'] . ";rmcnf:1[" . $dA['adults'] . ",0];dsti:" . $hotel->city_id_on_eurobookings . ";dstt:1;dsts:" . $hotel->city . ";frm:1;sort:0_desc;cur:" . $dA['currency'] . ";";
                $dA['hotel_name'] = $hotel->name;
                $dA['hotel_eurobooking_id'] = $hotel->eurobooking_id;
                $dA['hotel_uid'] = $hotel->uid;
                $dA['city'] = $hotel->city;

                echo $i++ . ' ';
                GatherRoomsDataJob::dispatch($hotelURL, $dA)->delay(now()->addSecond(1));
            }
            $dA['start_date'] = date("Y-m-d", strtotime("+1 day", strtotime($dA['start_date'])));
        }
    }
}
