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
            'United Kingdom of Great Britain and Northern Ireland',
            'Canada',
            'Spain',
            'Sweden',
            'Australia',
            'India',
            'China',
            'Japan',
            'Austria',
            'Belgium',
            'Bulgaria',
            'Croatia',
            'Cyprus',
            'Czech Republic',
            'Slovakia',
            'Slovenia',
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

        if ($result = DB::table('trends')->orderBy('s_no', 'desc')->first()) {
            $j = $result->s_no;
        } else {
            $j = 0;
        }

        $i = 0;
        foreach ($countries as $country) {
            $countrycode = DB::table('countries')->select('country_code', 'name')->where('name', '=', $country)->get();

            # This options are by default if none provided
            $options = [
                'hl' => 'en-US',
                'tz' => 0, # UTC
                'geo' => $countrycode[0]->country_code
            ];

            echo $countrycode[0]->country_code;
            $keywords = DB::table('keywords')->where([
                ['language', '=', 'EN']
//                ['s_no', '>', '181']
            ])->get();

            foreach ($keywords as $keyword) {

                if (!DB::table('trends')->where([
                    ['country_code', '=', $countrycode[0]->country_code],
                    ['keyword', '=', $keyword->keyword]
                ])->exists()) {

                    try {

                        sleep(1);
                        $gt = new GTrends($options);

                        $data = $gt->explore($keyword->keyword, 0, 'today 5-y');

                        if (isset($data)) {

                            DB::table('trends')->insert([
                                'uid' => uniqid(),
                                's_no' => ++$j,

                                'all_data' => serialize($data),
                                'keyword' => $keyword->keyword,
                                'keyword_language' => 'EN',
                                'api_preferred_language' => 'en-US',
                                'country_code' => $countrycode[0]->country_code,
                                'country_name' => $countrycode[0]->name,
                                'time' => 'today 5-y',

                                'source' => 'trends.google.com/trends/api/explore',

                                'created_at' => DB::raw('now()'),
                                'updated_at' => DB::raw('now()')
                            ]);
                            echo ++$i . ' Completed :' . $j . ' (' . $keyword->keyword . ') ' . $countrycode[0]->country_code . ' (' . $countrycode[0]->name . ') ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
                        }


                    } catch (\Exception $ex) {
                        if (!empty($ex)) {
                            $data = null;
                            $gt = null;
                            echo ++$i . ' Incomplete :' . $j . ' (' . $keyword->keyword . ') ' . $countrycode[0]->country_code . ' (' . $countrycode[0]->name . ') ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
//                            echo $ex . "\n";

                        }
                    }
                } else {
                    echo ++$i . ' Exists :' . $j . ' (' . $keyword->keyword . ') ' . $countrycode[0]->country_code . ' (' . $countrycode[0]->name . ') ' . Carbon\Carbon::now()->toDateTimeString() . "\n";
                }
            }
        }
    }
}
