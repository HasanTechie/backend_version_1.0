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
        $hotelUid = '5c80a2d79d162';
        $hotelName = 'Hotel Emona Aquaeductus';


        $hotelCompetitorArray[] = [
            'name' => 'Best Western Plus Hotel Universo',
            'uid' => '5c80a2de16914'
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Napoleon Hotel',
            'uid' => '5c80a2e45d1f3'
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Hotel Best Roma',
            'uid' => '5c80a2ea97a02'
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Hotel Latinum',
            'uid' => '5c80a2f073e78'
        ];
        $hotelCompetitorArray[] = [
            'name' => 'SHG Hotel Portamaggiore',
            'uid' => '5c80a2f649c4c'
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Hotel Novecento',
            'uid' => '5c80a2fc08711'
        ];
        $hotelCompetitorArray[] = [
            'name' => 'Hotel Bled',
            'uid' => '5c80a3019317b'
        ];

        $j = 0;
        foreach ($hotelCompetitorArray as $competitorHotel) {

            DB::table('hotels_competitors')->insert([
                'uid' => uniqid(),
                's_no' => $j++,
                'hotel_uid' => $hotelUid,
                'hotel_name' => $hotelName,
                'hotel_competitor_uid' => $competitorHotel['name'],
                'hotel_competitor_name' => $competitorHotel['uid'],
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);
        }

    }
}
