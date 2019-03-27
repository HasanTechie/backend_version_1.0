<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Hotel extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        return [
            'uid' => $this->uid,
            's_no' => $this->s_no,
            'name' => $this->name,
            'address' => $this->address,
            'total_rooms' => $this->total_rooms,
            'eurobooking_id' => $this->eurobooking_id,
            'photo' => $this->photo,
            'stars_category' => $this->stars_category,
            'ratings_on_tripadvisor' => (isset($this->ratings_on_tripadvisor) ? $this->ratings_on_tripadvisor : null),
            'total_number_of_ratings_on_tripadvisor' => (isset($this->total_number_of_ratings_on_tripadvisor) ? $this->total_number_of_ratings_on_tripadvisor : null),
            'ranking_on_tripadvisor' => (isset($this->ranking_on_tripadvisor) ? $this->ranking_on_tripadvisor : null),
            'badge_on_tripadvisor' => (isset($this->badge_on_tripadvisor) ? $this->badge_on_tripadvisor : null),
//                                                                    'ratings_on_google' => (isset($this->dataArray['ratings_on_google) ? $this->dataArray['ratings_on_google : null),
//                                                                    'total_number_of_ratings_on_google' => (isset($this->dataArray['total_number_of_ratings_on_google) ? $this->dataArray['total_number_of_ratings_on_google : null),
            'policies' => $this->policies,
            'city' => $this->city,
            'city_id_on_eurobookings' => $this->city_id_on_eurobookings,
            'country_code' => $this->country_code,
            'latitude_eurobookings' => (isset($this->latitude) ? $this->latitude : null),
//                                                                    'latitude_google' => (isset($this->dataArray['google_latitude) ? $this->dataArray['google_latitude : null),
            'longitude_eurobookings' => (isset($this->longitude) ? $this->longitude : null),
//                                                                    'longitude_google' => (isset($this->dataArray['google_longitude) ? $this->dataArray['google_longitude : null),
            'hid' => $this->hid,
            'hotel_url_on_eurobookings' => (isset($this->url) ? $this->url : null),
//                                                                    'all_data_google' => (isset($this->dataArray['all_data_google) ? $this->dataArray['all_data_google : null),
            'source' => $this->source,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
