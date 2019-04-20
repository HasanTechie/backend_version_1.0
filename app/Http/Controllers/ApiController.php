<?php

namespace App\Http\Controllers;

use App\Http\Resources\Hotel as HotelResource;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = 'KuKMQbgZPv0PRC6GqCMlDQ7fgdamsVY75FrQvHfoIbw4gBaG5UX0wfk6dugKxrtW';
//        $this->middleware('auth:admin');
    }
    public function HRSHotels($row, $apiKey)
    {
        if ($apiKey == $this->apiKey) {
            $hotels = DB::table('hotels_hrs')->limit($row)->get();
            return HotelResource::collection($hotels);
        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
