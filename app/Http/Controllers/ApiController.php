<?php

namespace App\Http\Controllers;

use App\Http\Resources\Hotel as HotelResource;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
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
