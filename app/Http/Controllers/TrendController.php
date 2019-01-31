<?php

namespace App\Http\Controllers;

use Google\GTrends;
use DB;

use App\Trend;
use Illuminate\Http\Request;

class TrendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $trends = Trend::paginate(25);
        return view('trends.index', compact('trends'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

//        $options = [
//            'hl' => 'en-US',
//            'tz' => 0, # UTC
//            'geo' => ''
//        ];
//        $gt = new GTrends($options);
//
//        //returns empty array
//        $data = $gt->explore('hotel');
//
//        dd($data); //var_dump equivalent in laravel
//
//
//        $keywordsArray[0]='hotel';
//        $keywordsArray[1]='car';
//        $keywordsArray[2]='facebook';
//        $keywordsArray[3]='ideas';
//        $keywordsArray[4]='ticket';
//
//        $countries = [
//            'United States',
//            'France',
//            'Germany',
//            'Russia',
//            'Netherlands',
//            'Brazil',
//            'United Kingdom',
//            'Canada',
//            'India',
//            'China',
//            'Japan',
//            'Australia',
//            'Austria',
//            'Belgium',
//            'Bulgaria',
//            'Croatia',
//            'Cyprus',
//            'Czech Republic',
//            'Slovakia',
//            'Slovenia',
//            'Spain',
//            'Sweden',
//            'Ukraine',
//            'Denmark',
//            'Estonia',
//            'Finland',
//            'Greece',
//            'Hungary',
//            'Ireland',
//            'Italy',
//            'Latvia',
//            'Lithuania',
//            'Luxembourg',
//            'Malta',
//            'Poland',
//            'Portugal',
//            'Romania',
//            'Egypt',
//            'Indonesia',
//            'Thailand',
//            'Pakistan',
//            'Mexico',
//            'Israel',
//            'South Africa',
//            'Chile',
//            'Argentina'
//        ];
//
//        foreach ($countries as $country) {
//            $countrycode = DB::table('countries')->select('iso_code', 'name')->where('name', '=', $country)->get();
////            echo $countrycode[0]->name . " ";
////            echo $countrycode[0]->iso_code . "<br>";
//
//            # This options are by default if none provided
//            $options = [
//                'hl' => 'en-US',
//                'tz' => 0, # UTC
//                'geo' => ''
//            ];
//
//            $keywords = DB::table('keywords')->where('language', '=', 'EN')->get();
//
//
//            $i = 0;
//            $keywordsArray = [];
//            foreach ($keywords as $keyword) {
//
//                if ($i % 5 == 0 && $i != 0) {
//
//                    $gt = new GTrends($options);
//
//                    $keywordsArray[0]='hotel';
//                    $keywordsArray[1]='car';
//                    $keywordsArray[2]='facebook';
//                    $keywordsArray[3]='ideas';
//                    $keywordsArray[4]='ticket';
//
//                    $data1 = $gt->explore($keywordsArray, 0, 'today 5-y');
//
//                    print_r($keywordsArray);
//                    dd($data1);
//
//                    $keywordsArray = [];
//                    $i = 0;
//
//                } else {
//                    $keywordsArray[$i++] = $keyword->keyword;
//                }
//
//
//            }
//        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Trend $trend
     * @return \Illuminate\Http\Response
     */
    public function show(Trend $trend)
    {
        //
        dd(unserialize($trend->all_data));
    }

    public function interestovertime(Trend $trend)
    {
        dd(unserialize($trend->all_data)['TIMESERIES']);
    }

    public function interestbysubregion(Trend $trend)
    {
        dd(unserialize($trend->all_data)['GEO_MAP']);
    }

    public function relatedtopics(Trend $trend)
    {
        dd(unserialize($trend->all_data)['RELATED_TOPICS']);
    }

    public function relatedqueries(Trend $trend)
    {
        dd(unserialize($trend->all_data)['RELATED_QUERIES']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Trend $trend
     * @return \Illuminate\Http\Response
     */
    public function edit(Trend $trend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Trend $trend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trend $trend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Trend $trend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trend $trend)
    {
        //
    }
}
