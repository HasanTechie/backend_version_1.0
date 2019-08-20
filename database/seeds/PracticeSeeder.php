<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use JonnyW\PhantomJs\Client as PhantomClient;
use Illuminate\Support\Facades\File;

class PracticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 0; $i < 365; $i++) {

            echo date("Y-m-d", strtotime($i . " day")) . ' ' . $i. "\n";
        }

        /*$notReservedJobs = DB::table('jobs')->select('id')->whereNull('reserved_at')->where('queue','=','default')->get();

        foreach($notReservedJobs as $notReservedJob){
            DB::table('jobs')->where('id', '=', $notReservedJob->id)->delete();
        }*/

        /*
                $filename = 'test_reservations.csv';
                $url = Storage::url('test_reservations.csv');
                $url = Storage::url($filename);

                dd($url);

                dd( response()->download(storage_path('app/public/' . $filename)));*/


//        dd(url('oauth/token'));

//        dd($rooms);
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

        */
        dd();
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
//            echo $i++ . ' ';
//        }
//        $i = 0;
//        while ($i < 800000) {

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
