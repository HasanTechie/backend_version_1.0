<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use JonnyW\PhantomJs\Client as PhantomClient;

class PracticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {




        $rooms = DB::table('rooms_hrs')->select('room')->distinct()->where('hotel_id','=',21)->get();


        dd($rooms);
        /*
        $date1 = date("Y-m-d", strtotime("+200 day"));
        $date2 = date("Y-m-d", strtotime("+300 day"));
        echo 'date1 : ' . $date1 . "\n";
        echo 'date2 : ' . $date2 . "\n";
        if ($date1 > $date2) {
            echo 'date1 > date2';
        } else {
            echo 'date1 not > date2';
        }

        echo "\n";
        dd();

        */
//        $url = 'http://falcon.proxyrotator.com:51337/?apiKey=6vuJP7FsAUfxqK4BQEhzweVYmDW2yMbN&get=true';
//        $ch = curl_init($url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
//        $response = curl_exec($ch);
//        curl_close($ch);
//
//        echo json_decode($response);

        //
        $started = microtime(true);
//        $i = 0;
//        while ($i < 864000) {
//
//            $rid = 'hrs1081Standardroomsingkaisaleroom1Incl' . mt_rand() . 'udingbreakfastClub,2Twin/SingleBed(s),25sqm/269sqft,Wirelessinternet';
////            $r = DB::table('rooms_hrs')->select('id')->where('rid', '=', $rid)->get();
//            echo $i++ . ' ';
//        }
//        $i = 0;
//        while ($i < 800000) {

        if (!File::exists(storage_path() . '/app/hrs/' . date("Y-m-d") . '/')) {
            Storage::makeDirectory('hrs/' . date("Y-m-d"));
        }
//        echo mt_rand(5, 500) . " ";
//            echo $i++ . " ";
//        }
//Execute your SQL query.

//Record the end time after the query has finished running.
        $end = microtime(true);

//Calculate the difference in microseconds.
        $difference = $end - $started;

//Format the time so that it only shows 10 decimal places.
        $queryTime = number_format($difference, 10);

//Print out the seconds it took for the query to execute.
        echo "SQL query took $queryTime seconds.";
        dd('break');

//        dd(hexdec('hrs389035Businessroomsingleroom1Reductionsforseniorcitizensagedandover(-EUR)Alsowithwritingdesk,sittingareaandInternetconnection'));
        $new = str_replace((range('a', 'z')), range(1, 26), 'hrs389035Businessroomsingleroom1Reductionsforseniorcitizensagedandover(-EUR)Alsowithwritingdesk,sittingareaandInternetconnection');
        dd($new);
        $r_id = DB::table('rooms_hrs')->insertGetId([
            'room' => 'test',
            'room_type' => 'test',
            'criteria' => 'test',
            'basic_conditions' => 'test',
            'photo' => 'test',
            'short_description' => 'test',
            'facilities' => 'test',
            'hotel_id' => 2,
            'rid' => 'test',
            'created_at' => DB::raw('now()'),
            'updated_at' => DB::raw('now()')
        ]);

        dd($r_id);

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
