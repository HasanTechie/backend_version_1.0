<?php

use Illuminate\Database\Seeder;
use Google\GTrends;

class GatheringGoogleTrendsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $countries = [
            'United States',
            'France',
            'Germany',
            'Russia',
            'Netherlands',
            'Brazil',
            'United Kingdom',
            'Canada',
            'India',
            'China',
            'Japan',
            'Australia',
            'Austria',
            'Belgium',
            'Bulgaria',
            'Croatia',
            'Cyprus',
            'Czech Republic',
            'Slovakia',
            'Slovenia',
            'Spain',
            'Sweden',
            'Ukraine',
            'Denmark',
            'Estonia',
            'Finland',
            'Greece',
            'Hungary',
            'Ireland',
            'Italy',
            'Latvia',
            'Lithuania',
            'Luxembourg',
            'Malta',
            'Poland',
            'Portugal',
            'Romania',
            'Egypt',
            'Indonesia',
            'Thailand',
            'Pakistan',
            'Mexico',
            'Israel',
            'South Africa',
            'Chile',
            'Argentina'
        ];

        foreach ($countries as $country) {
            $countrycode = DB::table('countries')->select('iso_code', 'name')->where('name', '=', $country)->get();
//            echo $countrycode[0]->name . " ";
//            echo $countrycode[0]->iso_code . "<br>";

            # This options are by default if none provided
            $options = [
                'hl' => 'en-US',
                'tz' => 0, # UTC
                'geo' => $countrycode[0]->iso_code
            ];

            $keywords = DB::table('keywords')->where('language', '=', 'EN')->get();


            $i = 0;
            $keywordsArray = [];
            foreach ($keywords as $keyword) {

                if ($i % 5 == 0 && $i != 0) {

                    $gt = new GTrends($options);
                    $data1 = $gt->explore($keywordsArray, 0, 'today 5-y');

                    dd($data1);

                    $keywordsArray = [];
                    $i = 0;

                } else {
                    $keywordsArray[$i++] = $keyword->keyword;
                }


            }
        }
    }
}
