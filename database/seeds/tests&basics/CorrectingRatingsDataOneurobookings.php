<?php

use Illuminate\Database\Seeder;

class CorrectingRatingsDataOneurobookings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $results = DB::table('rooms_prices_eurobookings')->whereNull('hotel_total_rooms')->get();

        foreach ($results as $instance) {

            $results2 = DB::table('rooms_prices_eurobookings')->where([
                ['hotel_total_rooms', '<>', null],
                ['hotel_address', '=', $instance->hotel_address],
                ['hotel_name', '=', $instance->hotel_name],
                ['hotel_city', '=', $instance->hotel_city],
            ])->get();

            if (isset($results2[0])) {

                DB::table('rooms_prices_eurobookings')
                    ->where([
                        ['hotel_address', $instance->hotel_address],
                        ['hotel_name', $instance->hotel_name],
                        ['hotel_eurobooking_id', null],
                        ['hotel_city', '=', $instance->hotel_city],
                    ])
                    ->update([
                        'hotel_uid' => $results2[0]->hotel_uid,
                        'hotel_total_rooms' => $results2[0]->hotel_total_rooms,
                        'hotel_eurobooking_id' => $results2[0]->hotel_eurobooking_id,
                        'hotel_eurobooking_img' => $results2[0]->hotel_eurobooking_img,
                        'hotel_stars_category' => $results2[0]->hotel_stars_category,
                        'hotel_ratings_on_tripadvisor' => $results2[0]->hotel_ratings_on_tripadvisor,
                        'hotel_number_of_ratings_on_tripadvisor' => $results2[0]->hotel_number_of_ratings_on_tripadvisor,
                        'hotel_ranking_on_tripadvisor' => $results2[0]->hotel_ranking_on_tripadvisor,
                        'hotel_badge_on_tripadvisor' => $results2[0]->hotel_badge_on_tripadvisor,
                    ]);

                echo $results2[0]->hotel_uid . ' ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
            }
        }
    }
}
