<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PracticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        while (0 == 0) {
//            echo hexdec(uniqid()) . "\n";
            echo microtime() + floor(rand()*10000) . "\n";
        }


        $competitorsData = DB::table('rooms_hrs')->join('prices_hrs', 'prices_hrs.rid', '=', 'rooms_hrs.rid')->select(DB::raw('hotel_name, ROUND(avg(price),2) as price, check_in_date, check_out_date'))->where([
            ['rooms_hrs.hotel_uid', '=', '5caa74bda4c89'],
            ['check_in_date', '=', '2019-04-21'],
        ])->groupBy('check_in_date')->get();

        dd($competitorsData);
        $rooms = DB::table('rooms_hrs')->get();

        foreach ($rooms as $room) {


            $da = substr(str_replace($room->currency, '', preg_replace('/[0-9.]+/', '', $room->criteria)), 0, 50);

            dd($da);
        }

        dd('raeched');
        $count = 0;

        dd($count);

        $full = '99500';
        $cents = '00';
        $price = preg_replace('/' . trim($cents) . '$/', '', $full) . '.' . $cents;
        dd($price);

        dd(date("Y-m-d", strtotime("+1 day")));

        $text = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium deloris dolorum et oris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odeveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odeniti et libero maiores nostrum. Aliquoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nuoris dolorum et eveniet in ioris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odste non nulla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odlla odoris dolorum et eveniet in iste non nulla odoris dolorum et eveniet in iste non nulla odam corporis dolorum et eveniet in iste non nulla odio optio quaerat quasi quo, rem tempora?Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium deleniti et libero maiores nostrum. Aliquam corporis dolorum et eveniet in iste non nulla odio optio quaerat quasi quo, rem temporaLorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium deleniti et libero maiores nostrum. Aliquam corporis dolorum et eveniet in iste non nulla odio optio quaerat quasi quo, rem tempora??';


        dd(substr(str_replace(' ', '', $text), 0, 240));
    }
}
