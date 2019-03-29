<?php

use Illuminate\Database\Seeder;

class Rooms_eurobookings_SeederSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $currency = 'EUR';
        $adults = 2;
        $checkInDate = '2019-04-12';
        $checkOutData = '2019-04-13';

        $hotels = DB::table('hotels_eurobookings_data')->inRandomOrder()->get();

        foreach ($hotels as $hotel) {
            $hotelBasicURL = explode('?', $hotel->hotel_url_on_eurobookings);

            $hotelURL = $hotelBasicURL[0] . "?q=start:$checkInDate;end:$checkOutData;rmcnf:1[" . $adults . ",0];dsti:" . $hotel->city_id_on_eurobookings . ";dstt:1;dsts:" . $hotel->city . ";frm:1;sort:0_desc;cur:$currency;";

            dd($hotelURL);
        }
    }
}
