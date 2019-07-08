<?php

use Illuminate\Database\Seeder;

class EnteringHotelCompetitorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $hotelUid = '5c878f0d161a5';
        $hotelName = 'Emona Aquaeductus';


       /* $hotelCompetitorArray[] = [
            'name' => 'Best Western Plus Hotel Universo',
            'uid' => '5c80a2de16914',
            'hotel_provider' => 'eurobookings.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Napoleon Hotel',
            'uid' => '5c80a2e45d1f3',
            'hotel_provider' => 'eurobookings.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Hotel Best Roma',
            'uid' => '5c80a2ea97a02',
            'hotel_provider' => 'eurobookings.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Hotel Latinum',
            'uid' => '5c80a2f073e78',
            'hotel_provider' => 'eurobookings.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'SHG Hotel Portamaggiore',
            'uid' => '5c80a2f649c4c',
            'hotel_provider' => 'eurobookings.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Hotel Novecento',
            'uid' => '5c80a2fc08711',
            'hotel_provider' => 'eurobookings.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Hotel Bled',
            'uid' => '5c80a3019317b',
            'hotel_provider' => 'eurobookings.com',
        ];*/

/*

        $hotelCompetitorArray[] = [
            'name' => 'Best Western Plus Hotel Universo',
            'uid' => '5c878746e093f',
            'hotel_provider' => 'hrs.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Napoleon',
            'uid' => '5c87892e8bbfe',
            'hotel_provider' => 'hrs.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Best Roma',
            'uid' => '5c87a92647e8f',
            'hotel_provider' => 'hrs.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Portamaggiore Shg Hotel',
            'uid' => '5c87b7025409d',
            'hotel_provider' => 'hrs.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Novecento',
            'uid' => '5c879059a6d60',
            'hotel_provider' => 'hrs.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Bled',
            'uid' => '5c878a5aa27c3',
            'hotel_provider' => 'hrs.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Hotel Milton Roma',
            'uid' => '5c878b020bb16',
            'hotel_provider' => 'hrs.com',
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Infinito',
            'uid' => '5c879e6fc1545',
            'hotel_provider' => 'hrs.com',
        ];*/


        $j = 0;
        foreach ($hotelCompetitorArray as $competitorHotel) {

            DB::table('hotels_competitors')->insert([
                'uid' => uniqid(),
                's_no' => $j++,
                'hotel_uid' => $hotelUid,
                'hotel_name' => $hotelName,
                'hotel_competitor_uid' => $competitorHotel['uid'],
                'hotel_competitor_name' => $competitorHotel['name'],
                'hotel_provider' => $competitorHotel['hotel_provider'],
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);
        }

    }
}
