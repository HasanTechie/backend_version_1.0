<?php

use Illuminate\Database\Seeder;

class ConvertingUIDsOfTables extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $hotels = DB::table('hotels_basic_data_for_gathering')->get();
        $i = 1;
        foreach ($hotels as $hotel) {
            /*DB::table('hotels_basic_data_for_gathering')
                ->where('uid', $hotel->uid)
                ->update(['uid' => $i]);*/
            /*DB::table('rooms_hrs')
                ->where('hotel_uid', $hotel->uid)
                ->update(['hotel_uid' => $i]);*/
            $i++;
            echo $i . ' ';
        }


     /*   $rooms = DB::table('rooms_hrs')->get();

        $i = 1;
        foreach ($rooms as $room) {
            DB::table('rooms_hrs')
                ->where('rid', $room->rid)
                ->update(['rid' => $i]);
            DB::table('prices_hrs')
                ->where('rid', $room->rid)
                ->update(['rid' => $i]);
            $i++;
            echo $i . ' ';
        }*/
    }
}
