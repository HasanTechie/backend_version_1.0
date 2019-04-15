<?php

namespace App\Http\Controllers;

//use Goutte\Client;
use DB;

//use phpDocumentor\Reflection\Types\Array_;
//use SKAgarwal\GoogleApi\PlacesApi;

use App\Hotel;
use App\Http\Resources\Hotel as HotelResource;
use App\Http\Resources\RoomPrice as RoomPriceResource;
use App\Http\Resources\CompetitorPrice as CompetitorPriceResource;


use Illuminate\Http\Request;

class HotelController extends Controller
{

    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = 'KuKMQbgZPv0PRC6GqCMlDQ7fgdamsVY75FrQvHfoIbw4gBaG5UX0wfk6dugKxrtW';
//        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($row, $apiKey)
    {
        //
//        $hotels = DB::table('hotels_eurobookings')->where('city','=','Berlin')->orderBy('total_number_of_ratings_on_tripadvisor')->get();
        if ($apiKey == $this->apiKey) {
            $hotels = DB::table('hotels_eurobookings')->limit($row)->get();
            return HotelResource::collection($hotels);
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function search()
    {
//        dd('s');
//        return view('hotels.search');
        return redirect('/');;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*//
//        $client = new Client();
//
        $url = "https://www.gruppoloan.it/hdc/en/";
//        $crawler = $client->request('GET', $url);
//
//        $form = $crawler->filter('form[name="formFR"]')->form();
//        $crawler = $client->submit($form, array('datepicker' => date("Y-m-d")));
//
//        dd($crawler->text());

        $client = new Client();
//        $crawler = $client->request('GET', 'https://www.gruppoloan.it/hdc/en/');
//        // select the form and fill in some values
//        $form = $crawler->filter('div.col-sm-12.col-md-11.col-md-offset-1.content_fastR > form')->form();
//        $form['datepicker'] = date("Y-m-d", strtotime("+25 day", strtotime(date("Y-m-d"))));
//        // submit that form
//        $crawler = $client->submit($form);


        $crawler = $client->request('GET', $url);
        $form = $crawler->filter("div.col-sm-12.col-md-11.col-md-offset-1.content_fastR > form")->form();
        $form['datepicker'] = date("d/m/Y", strtotime("+25 day", strtotime(date("Y-m-d"))));
        $form['NUMNOTTI'] = 1;
        $form['NUMCAMERE'] = 1;
        $form['NUMPERSONE'] = 1;
        $crawler = $client->submit($form);

        dd($crawler);*/
    }


    public function test2()
    {
        $results = DB::select('select * from hotels where id = :id', ['id' => '1']);

        dd(unserialize($results[0]->all_data));

    }

    public function test3()
    {
        $results = DB::select('select * from places where id = :id', ['id' => '99950']);

        dd(unserialize($results[0]->all_data));

    }

    public function hotelEurobookingsShowAll()
    {
        $hotels = DB::table('hotels_eurobookings')
            ->paginate(20);

        return view('hotels.eurobookings.index', compact('hotels'));
    }

    public function hotelEurobookingsShowDetails($id)
    {
        $hotels = DB::table('hotels_eurobookings')->where('uid', '=', $id)->get();

        dd($hotels[0]);
    }


    public function hotelEurobookingsReviewsOnTripadvisor($id)
    {
        $hotels = DB::table('hotels_eurobookings')->where('uid', '=', $id)->get();

        dd(unserialize($hotels[0]->reviews_on_tripadvisor));
    }


    public function hotelEurobookingsDetails($id)
    {
        $hotels = DB::table('hotels_eurobookings')->where('uid', '=', $id)->get();

        dd(unserialize($hotels[0]->details));
    }


    public function hotelEurobookingsFacilities($id)
    {
        $hotels = DB::table('hotels_eurobookings')->where('uid', '=', $id)->get();

        dd(unserialize($hotels[0]->facilities));
    }


    public function hotelEurobookingsHotelInfo($id)
    {
        $hotels = DB::table('hotels_eurobookings')->where('uid', '=', $id)->get();

        dd(unserialize($hotels[0]->hotel_info));
    }


    public function hotelHRSShowAll()
    {
        $hotels = DB::table('hotels_hrs')
            ->paginate(20);

        return view('hotels.hrs.index', compact('hotels'));
    }

    public function hotelHRSShowDetails($id)
    {
        $hotels = DB::table('hotels_hrs')->where('uid', '=', $id)->get();

        dd($hotels[0]);
    }


    public function getPlaces()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "http://tour-pedia.org/api/getPlaces?location=Berlin"); //free but only one result

        dd(json_decode($response->getBody()));
    }

    public function getPlaceDetails()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "http://tour-pedia.org/api/getPlaceDetails?id=204042");

        dd(json_decode($response->getBody()));
    }

    public function getReviewDetails()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "http://tour-pedia.org/api/getReviewDetails?id=53513358ae9eef9405b2d9ac"); //free
        dd(json_decode($response->getBody()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Hotel $hotel
     * @return \Illuminate\Http\Response
     */
    public function show($hotel, $dateFrom, $dateTo, $apiKey)
    {
        //
        if ($apiKey == $this->apiKey) {
            $hotels = DB::table('rooms_prices_eurobookings')->select(DB::raw('uid, avg(price) as price, check_in_date'))->where([
                ['hotel_uid', '=', $hotel],
                ['check_in_date', '>=', $dateFrom],
                ['check_in_date', '<=', $dateTo],
            ])->limit(12)->groupBy('check_in_date')->get();

            return RoomPriceResource::collection($hotels);
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    public function showCompetitor($hotel, $dateFrom, $dateTo, $apiKey)
    {
        //
        if ($apiKey == $this->apiKey) {
            $dates = DB::table('rooms_prices_eurobookings')->select('check_in_date')->distinct('check_in_date')->where([
                ['hotel_uid', '=', $hotel],
                ['check_in_date', '>=', $dateFrom],
                ['check_in_date', '<=', $dateTo],
            ])->orderBy('check_in_date')->get();
            $competitorRooms = [];
            foreach ($dates as $date) {

                $CompetitorHotels = DB::table('hotels_competitors')->where('hotel_uid', '=', $hotel)->get();

                foreach ($CompetitorHotels as $competitorHotel) {
                    $competitorRooms = DB::table('rooms_prices_eurobookings_data')->where([
                        ['check_in_date', '=', $date->check_in_date],
                        ['hotel_uid', '=', $competitorHotel->hotel_competitor_uid],
                    ])->get();
                }
            }
            return CompetitorPriceResource::collection($competitorRooms);
        } else {
            dd('Error: Incorrect API Key');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Hotel $hotel
     * @return \Illuminate\Http\Response
     */
    public function edit(Hotel $hotel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Hotel $hotel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hotel $hotel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Hotel $hotel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotel $hotel)
    {
        //
    }
}


/*
        // for merging data of tourpedia and google
        ini_set('memory_limit', '8192M');
        $places = DB::table('places')->get();
        foreach ($places as $place){
            if((strcasecmp($place->subCategory, 'hotel') == 0) || (stripos(strtolower($place->name),'hotel') !== false)) // case insensitive comparisons
                DB::table('hotels')->insert([
                    'name' => (isset($place->name) ? $place->name : 'Not Available'),
                    'tourpedia_id' => (isset($place->place_id) ? $place->place_id : null),
                    'total_rooms' => (isset($place->totalrooms) ? $place->totalrooms : null),
                    'country' => (isset($place->country) ? $place->country : 'Not Available'),
                    'city' => (isset($place->location) ? $place->location : 'Not Available'),
                    'address' => $place->address,
                    'international_phone' => (isset($place->international_phone_number) ? $place->international_phone_number : 'Not Available'),
                    'latitude' => $place->lat,
                    'longitude' => $place->lat,
                    'tourpedia_numReviews' => $place->numReviews,
                    'tourpedia_reviews' => $place->reviews,
                    'tourpedia_polarity' => $place->polarity,
                    'tourpedia_details' => $place->details,
                    'tourpedia_originalId' => $place->originalId,
                    'source' => $place->source,
                    'phone' => $place->phone_number,
                    'website' => $place->website,
                    'description' => $place->description,
                    'tourpedia_external_urls' => $place->external_urls,
                    'tourpedia_statistics' => $place->statistics,
                    'tourpedia_reviews_ids' => $place->reviews_ids,
                    'tourpedia_detailed_reviews' => $place->detailed_reviews,
                    'all_data' => serialize($place),
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()')
                ]);
            }
            /*
             * !tourpedia_id = place_id
             * name = name
             * address = address
             * !!category
             * city = location
             * latitude = lat
             * longitude = lng
             * !tourpedia_numReviews = numReviews
             * !tourpedia_reviews = reviews
             * !tourpedia_polarity = polarity
             * !tourpedia_details = details
             * !tourpedia_originalId = originalId
             * !source = source
             * phone = phone_number
             * international_phone = international_phone_number
             * website = website
             * !description = description
             * !tourpedia_external_urls = external_urls
             * !tourpedia_statistics = statistics
             * !tourpedia_reviews_ids = reviews_ids
             * !tourpedia_detailed_reviews = detailed_reviews
             * all_data = all_data
             *


        */

// Unbeatabil => AIzaSyBA1e2qFRgt6xW17Goo_IwPAWkCcrqXTqY
// soliDPS => AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg
// $key = 'AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg';

/*
session_start();
$_SESSION['next_page_token']='';
*/

/*
$googlePlaces = new PlacesApi($key);
$response = $googlePlaces->placeAutocomplete('hotels in berlin');
*/

/*
$client = new \GuzzleHttp\Client();
//        $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=Lindner%20Hotel%20Am%20Ku'damm&inputtype=textquery&key=$key"); //free but only one result
$response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJoWWNpP5QqEcRiCJoLCXTM2o&key=$key");
//        if (!empty($_SESSION['next_page_token'])) {
//            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Hotels%20in%20Frankfurt,%20Germany&key=$key&pagetoken=" . $_SESSION['next_page_token'] . "");
//        } else {
//            $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Hotels%20in%20Frankfurt,%20Germany&key=$key");
//        }

dd(json_decode($response->getBody()));
/*
$hotels = DB::table('hotels')->select('hotel_id')->get();

foreach ($hotels as $hotel) {
    $hotel_id = $hotel->hotel_id;
    $response = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?placeid=$hotel_id&key=$key");

    $response = json_decode($response->getBody());

    $response = $response->result;

    $address_components = [];

    foreach ($response->address_components as $componentInstance) {
        foreach ($componentInstance->types as $type)

            if (!is_array($type)) {
                if ($type == 'country') {
                    $address_components['country'] = $componentInstance->long_name;
                }
                if ($type == 'locality') {
                    $address_components['locality'] = $componentInstance->long_name;
                }
                if ($type == 'sublocality') {
                    $address_components['sublocality'] = $componentInstance->long_name;
                }
                if ($type == 'route') {
                    $address_components['route'] = $componentInstance->long_name;
                }
                if ($type == 'street_number') {
                    $address_components['street_number'] = $componentInstance->long_name;
                }
                if ($type == 'postal_code') {
                    $address_components['postal_code'] = $componentInstance->long_name;
                }
            }
    }

    DB::table('hotels')
        ->where('hotel_id', $hotel_id)
        ->update([
            'website' => $response->website,
            'phone' => $response->formatted_phone_number,
            'international_phone' => $response->international_phone_number,
            'country' => $address_components['country'],
            'locality' => $address_components['locality'],
            'sublocality' => $address_components['sublocality'],
            'route' => $address_components['route'],
            'street_number' => $address_components['street_number'],
            'postal_code' => $address_components['postal_code'],
            'address_components' => serialize($response->address_components),
            'opening_hours' => serialize($response->opening_hours),
            'photos' => serialize($response->photos),
            'reviews' => serialize($response->reviews),
            'adr_address' => $response->adr_address,
            'maps_url' => $response->url,
            'vicinity' => $response->vicinity,
            'total_ratings' => $response->user_ratings_total,
            'rating' => $response->rating,
            'name' => $response->name,
            'unk_id' => $response->id,
            'address' => $response->formatted_address,
            'geometry' => serialize($response->geometry),
            'plus_code' => serialize($response->plus_code),
            'rating' => $response->rating,
            'all_data' => serialize($response),

        ]);
}
*/


/*
//asynchronous
$request = new \GuzzleHttp\Psr7\Request('GET', 'https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJ42MzYk1QqEcRhlX0r_-WtI8&fields=name,rating,formatted_phone_number&key=AIzaSyA5UftG8KTrwTL_FR6LFY7iH7P51Tim3Cg');
$promise = $client->sendAsync($request)->then(function ($response) {
    echo 'I completed! ' . $response->getBody();
});
$promise->wait();
*/


/*
if (!empty($res->next_page_token)) {
    $_SESSION['next_page_token'] = $res->next_page_token;
}
*/

/*
foreach ($res->result as $hotelinstance) {

    $results = DB::select('select * from hotels where hotel_id = :id', ['id' => "$hotelinstance->place_id"]);

    if (empty($results)) {

        $hotel = new Hotel();

        $hotel->name = $hotelinstance->name;
        $hotel->hotel_id = $hotelinstance->place_id;
        $hotel->unk_id = $hotelinstance->id;
        $hotel->address = $hotelinstance->formatted_address;
        $hotel->geometry = serialize($hotelinstance->geometry);
        $hotel->plus_code = serialize($hotelinstance->plus_code);
        $hotel->rating = $hotelinstance->rating;
        $hotel->total_ratings = $hotelinstance->user_ratings_total;
        $hotel->all_data = serialize($res->result);
        $hotel->save();

        dd($res);
    }
}
*/

/*
 // FOR makcorps API
$data = [
    'username' => 'sayyid',
    'password' => 'minimum;\'90',
];

$curl1 = curl_init();

curl_setopt_array($curl1, array(
    CURLOPT_URL => "https://api.makcorps.com/auth",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30000,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
        // Set here requred headers */
//                "accept: */*",
/*                       "accept-language: en-US,en;q=0.8",
                        "content-type: application/json",
                    ),
                ));

                $response1 = curl_exec($curl1);
                $err = curl_error($curl1);

                curl_close($curl1);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    $token = (json_decode($response1)->access_token);
                }


                $curl = curl_init();

        //        $url="https://api.makcorps.com/king/v2/berlin/2019-01-18/2019-01-20";
                $url="https://api.makcorps.com/free/berlin";

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "$url",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        // Set Here Your Requesred Headers
                        'Content-Type: application/json',
                        "Authorization: JWT $token"
                    ),
                ));

        //        dd($token);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    dd(json_decode($response));
                }
                */

/*
// for tour-pedia.org all data
    $cities = array("Amsterdam", "Barcelona", "Berlin", "Dubai", "London", "Paris", "Rome", "Tuscany");

    foreach ($cities as $city) {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "http://tour-pedia.org/api/getPlaces?location=$city"); //free but only one result

        $response = json_decode($response->getBody());

        foreach ($response as $instance) {
            //place_id,name,address,category,location,lat,lng,numReviews,reviews,polarity,details,originalId,subCategory,source
            DB::table('places')->insert([
                'place_id' => (isset($instance->id) ? $instance->id : null),
                'name' => (isset($instance->name) ? $instance->name : 'Not Available'),
                'address' => (isset($instance->address) ? $instance->address : 'Not Available'),
                'category' => (isset($instance->category) ? $instance->category : 'Not Available'),
                'location' => (isset($instance->location) ? $instance->location : 'Not Available'),
                'lat' => (isset($instance->lat) ? $instance->lat : null),
                'lng' => (isset($instance->lng) ? $instance->lng : null),
                'numReviews' => (isset($instance->numReviews) ? $instance->numReviews : null),
                'reviews' => (isset($instance->reviews) ? $instance->reviews : 'Not Available'),
                'polarity' => (isset($instance->polarity) ? $instance->polarity : null),
                'details' => (isset($instance->details) ? $instance->details : 'Not Available'),
                'originalId' => (isset($instance->originalId) ? $instance->originalId : 'Not Available'),
                'subCategory' => (isset($instance->subCategory) ? $instance->subCategory : 'Not Available'),
                'source' => 'http://tour-pedia.org',
                'all_data' => serialize($instance),
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);

            dd($instance);
        }
    }
*/

/*
// details and review details
$cities = array("Amsterdam", "Barcelona", "Berlin", "Dubai", "London", "Paris", "Rome", "Tuscany");

foreach ($cities as $city) {
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', "http://tour-pedia.org/api/getPlaces?location=$city"); //free but only one result

    if ($response->getStatusCode() == 200) {


        $response = json_decode($response->getBody());

        foreach ($response as $instance) {
            //place_id,name,address,category,location,lat,lng,numReviews,reviews,polarity,details,originalId,subCategory,source
            DB::table('places')->insert([
                'place_id' => (isset($instance->id) ? $instance->id : null),
                'name' => (isset($instance->name) ? $instance->name : 'Not Available'),
                'address' => (isset($instance->address) ? $instance->address : 'Not Available'),
                'category' => (isset($instance->category) ? $instance->category : 'Not Available'),
                'location' => (isset($instance->location) ? $instance->location : 'Not Available'),
                'lat' => (isset($instance->lat) ? $instance->lat : null),
                'lng' => (isset($instance->lng) ? $instance->lng : null),
                'numReviews' => (isset($instance->numReviews) ? $instance->numReviews : null),
                'reviews' => (isset($instance->reviews) ? $instance->reviews : 'Not Available'),
                'polarity' => (isset($instance->polarity) ? $instance->polarity : null),
                'details' => (isset($instance->details) ? $instance->details : 'Not Available'),
                'originalId' => (isset($instance->originalId) ? $instance->originalId : 'Not Available'),
                'subCategory' => (isset($instance->subCategory) ? $instance->subCategory : 'Not Available'),
                'source' => 'http://tour-pedia.org',
                'all_data' => serialize($instance),
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);

//                $places = DB::table('places')->select('place_id')->get();
            $place = $instance->id;
            $client = new \GuzzleHttp\Client();
            $response1 = $client->request('GET', "http://tour-pedia.org/api/getPlaceDetails?id=$place"); //free

            if ($response1->getStatusCode() == 200) {
                $response1 = json_decode($response1->getBody());

//            dd($response1->id);
                $reviewsArray = [];
                $i = 0;

                if (!empty($response1->reviews)) {
                    if (is_array($response1->reviews)) {


                        foreach ($response1->reviews as $id) {
                            $client = new \GuzzleHttp\Client();
                            $response11 = $client->request('GET', "http://tour-pedia.org/api/getReviewDetails?id=$id"); //free
                            if ($response11->getStatusCode() == 200) {
                                $reviewsArray[$i] = json_decode($response11->getBody());
                                $i++;
                            }
                        }
                    }
                }

//            dd(serialize($reviewsArray));

                DB::table('places')
                    ->where('place_id', $response1->id)
                    ->update([
                        'name' => (isset($response1->name) ? $response1->name : 'Not Available'),
                        'address' => (isset($response1->address) ? $response1->address : 'Not Available'),
                        'phone_number' => (isset($response1->phone_number) ? $response1->phone_number : 'Not Available'),
                        'international_phone_number' => (isset($instance->international_phone_number) ? $response1->international_phone_number : 'Not Available'),
                        'website' => (isset($response1->website) ? $response1->website : 'Not Available'),
                        'category' => (isset($response1->category) ? $response1->category : 'Not Available'),
                        'location' => (isset($response1->location) ? $response1->location : 'Not Available'),
                        'lat' => (isset($response1->lat) ? $response1->lat : null),
                        'lng' => (isset($response1->lng) ? $response1->lng : null),
                        'numReviews' => (isset($response1->numReviews) ? $response1->numReviews : null),
                        'polarity' => (isset($response1->polarity) ? $response1->polarity : null),
                        'details' => (isset($response1->details) ? $response1->details : 'Not Available'),
                        'originalId' => (isset($response1->originalId) ? $response1->originalId : 'Not Available'),
                        'subCategory' => (isset($response1->subCategory) ? $response1->subCategory : 'Not Available'),
                        'source' => 'http://tour-pedia.org',
                        'description' => (isset($response1->description) ? serialize($response1->description) : 'Not Available'),
                        'external_urls' => (isset($response1->external_urls) ? serialize($response1->external_urls) : 'Not Available'),
                        'statistics' => (isset($response1->statistics) ? serialize($response1->statistics) : 'Not Available'),
                        'reviews_ids' => (isset($response1->reviews) ? serialize($response1->reviews) : 'Not Available'),
                        'detailed_reviews' => (isset($reviewsArray) ? serialize($reviewsArray) : 'Not Available'),
                        'all_data_detailed' => serialize($response1)
                    ]);
            }
        }
    }
}
*/


//test1
/*
        //
        $hotels = Hotels::get(); //limit to 2000

//        $res = unserialize($hotel[0]->all_data);
            $data = "";
        foreach ($hotels as $instance){
//            $hotel = new Hotel();
//            $hotel->address= $instance->formatted_address;
//
            dd(unserialize($instance->all_data));
        }
        dd($res);
        dd($data);
        return view('flights.index', compact('flights'));
        */
