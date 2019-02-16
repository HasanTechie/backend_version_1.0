<?php

use Illuminate\Database\Seeder;

class FixinguidOfVerticalBookings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }
}
/*
        $results = DB::table('rooms_prices_vertical_booking')->get();

        foreach ($results as $instance) {

            DB::table('rooms_prices_vertical_booking')->where('uid', $instance->uid)->update([
                'uid' => uniqid()
            ]);
            echo Carbon\Carbon::now()->toDateTimeString() . "\n";
        }
*/
/*
        $hotels = DB::table('hotels')->orderBy('s_no', 'desc')->get();

        foreach ($hotels as $hotel) {
            $rooms = DB::table('rooms_prices_vertical_booking')->where([
                ['hotel_name', '=', $hotel->name],
                ['hotel_address', '=', $hotel->address],
                ['hotel_city', '=', $hotel->city],
                ['hotel_phone', '=', $hotel->phone],
                ['hotel_website', '=', $hotel->website],
            ])->get();

            if (count($rooms) > 0) {

                DB::table('rooms_prices_vertical_booking')
                    ->where('hotel_name', $hotel->name)
                    ->update(['hotel_uid' => $hotel->uid]);

                echo $hotel->uid . ' ' . Carbon\Carbon::now()->toDateTimeString() . ' ' . $hotel->s_no . ' ' . "\n";
            }
        }
        */
