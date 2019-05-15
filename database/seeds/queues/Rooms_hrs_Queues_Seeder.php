<?php

use App\Jobs\GatherRoomsDataJob;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class Rooms_hrs_Queues_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $dA['currency'] = 'EUR';
        $dA['adults'] = [2];

        $hotels = DB::table('hotels_hrs')->select('id', 'hrs_id', 'city')->whereIn('city', ['Rome', 'Berlin'])->get();

        foreach ($hotels as $hotel) {
            $dA['start_date'] = date("Y-m-d", strtotime("+1 day"));
            $dA['end_date'] = date("Y-m-d", strtotime("+365 day"));
            $dA['hotel_id'] = $hotel->id;
            $dA['hotel_hrs_id'] = $hotel->hrs_id;
            $dA['city'] = $hotel->city;

            while (strtotime($dA['start_date']) <= strtotime($dA['end_date'])) {
                $dA['check_in_date'] = $dA['start_date'];
                $dA['check_out_date'] = date("Y-m-d", strtotime("+1 day", strtotime($dA['start_date'])));
                foreach ($dA['adults'] as $adult) {
                    $dA['adult'] = $adult;
                    $dA['request_url'] = "https://www.hrs.com/hotelData.do?hotelnumber=" . $dA['hotel_hrs_id'] .
                        "&activity=offer&availability=true&l=en&customerId=413388037&forwardName=defaultSearch&searchType=default&xdynpar_dyn=&fwd=gbgCt&client=en&currency=" .
                        $dA['currency'] . "&startDateDay=" . date("d", strtotime($dA['check_in_date'])) . "&startDateMonth=" .
                        date("m", strtotime($dA['check_in_date'])) . "&startDateYear=" . date("Y", strtotime($dA['check_in_date'])) .
                        "&endDateDay=" . date("d", strtotime($dA['check_out_date'])) . "&endDateMonth=" . date("m", strtotime($dA['check_out_date'])) .
                        "&endDateYear=" . date("Y", strtotime($dA['check_out_date'])) . "&adults=$adult&singleRooms=" . (($adult == 1) ? 1 : 0) . "&doubleRooms=" .
                        (($adult > 1) ? 1 : 0) . "&children=0";

                    GatherRoomsDataJob::dispatch($dA)->delay(now()->addSecond(mt_rand(5, 500)));
                }
                if ($dA['start_date'] < date("Y-m-d", strtotime("+240 day"))) {
                    $dA['start_date'] = date("Y-m-d", strtotime("+1 day", strtotime($dA['start_date'])));
                } else {
                    $dA['start_date'] = date("Y-m-d", strtotime("+7 day", strtotime($dA['start_date'])));
                }
            }
        }
    }
}
