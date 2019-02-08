<?php

use Illuminate\Database\Seeder;

class StripingTagsOfDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $results = DB::table('rooms_prices_hotel_baglioni')->get();

        foreach ($results as $instance1) {
            $i=0;

            foreach (unserialize($instance1->room_all_rates_and_details) as $instance2) {


                $da['striked_price'] = str_replace(' ', '', trim(str_replace(array("\r", "\n"), '', $instance2['striked_price'])));
                $da['display_price'] = $instance2['display_price'];
                $da['currency'] = 'EUR';
                $da['pernight'] = $instance2['pernight'];
                $da['room_type'] = $instance2['room_type'];
                $da['rate_type'] = $instance2['rate_type'];
                $da['value_before_tax'] = $instance2['value_before_tax'];
                $da['tax'] = $instance2['tax'];
                $da['total_including_tax'] = $instance2['total_including_tax'];
                $da['date_from_field'] = $instance2['date_from_field'];
                $da['short_description'] = trim(str_replace(array("\r", "\n"), '', $instance2['short_description']));
                $da['description'] = trim(str_replace(array("\r", "\n"), '', $instance2['description']));

                $dataArray[$i++]=$da;

            }
            DB::table('rooms_prices_hotel_baglioni')->where('uid', $instance1->uid)->update([
                'room_all_rates_and_details' => serialize($dataArray),
            ]);
            echo $instance1->s_no . "\n";
        }

    }
}

/*
$results = DB::table('rooms_prices_hotel_baglioni')->get();

        foreach ($results as $instance1) {
            $i=0;

            foreach (unserialize($instance1->room_all_rates_and_details) as $instance2) {

                $da['total_including_tax'] = $instance2['total_including_tax'];

                $dataArray[$i++]=$da;

                if($instance1->lowest_price ==0){
                    DB::table('rooms_prices_hotel_baglioni')->where([
                        ['uid', $instance1->uid],
                    ])->update([
                        'lowest_price' => trim(str_replace('EUR', null, $da['total_including_tax'])),
                    ]);
                    break;
                }

            }
            echo $instance1->s_no . "\n";
        }
*/
