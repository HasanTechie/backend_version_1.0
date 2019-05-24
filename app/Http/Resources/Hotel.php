<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Hotel extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        return [
            'hotel_id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'status' => true
        ];
    }
}
/* 'hotel_id' => $this->id,
            's_no' => $this->s_no,
            'name' => $this->name,
            'address' => $this->address,
            'photo' => $this->photo,
            'hrs_id' => $this->hrs_id,
            'city' => $this->city,
            'city_id_on_hrs' => $this->city_id_on_hrs,
            'country_code' => $this->country_code,
            'ratings_on_hrs' => (isset($this->ratings_on_hrs) ? $this->ratings_on_hrs : null),
            'ratings_text_on_hrs' => (isset($this->ratings_text_on_hrs) ? $this->ratings_text_on_hrs : null),
            'total_number_of_ratings_on_hrs' => (isset($this->total_number_of_ratings_on_hrs) ? $this->total_number_of_ratings_on_hrs : null),
            'ratings_on_google' => (isset($this->ratings_on_google) ? $this->ratings_on_google : null),
            'total_number_of_ratings_on_google' => (isset($this->total_number_of_ratings_on_google) ? $this->total_number_of_ratings_on_google : null),
            'location_details' => $this->location_details,
            'surroundings_of_the_hotel' => $this->surroundings_of_the_hotel,
            'sports_leisure_facilities' => $this->sports_leisure_facilities,
            'nearby_airports' => $this->nearby_airports,
            'details' => $this->details,
            'facilities' => $this->facilities,
            'in_house_services' => $this->in_house_services,


            'latitude_hrs' => (isset($this->latitude) ? $this->latitude : null),
            'longitude_hrs' => (isset($this->longitude) ? $this->longitude : null),
            'latitude_google' => $this->latitude_google,
            'longitude_google' => $this->longitude_google,
            'hotel_url_on_hrs' => (isset($this->url) ? $this->url : null),
            'hid' => $this->hid,
            'all_data_google' => $this->all_data_google,
            'source' => $this->source,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at*/
/*
  return [
            'uid' => $this->uid,
            's_no' => $this->s_no,
            'name' => $this->name,
            'address' => $this->address,
            'total_rooms' => $this->total_rooms,
            'eurobooking_id' => $this->eurobooking_id,
            'photo' => $this->photo,
            'stars_category' => $this->stars_category,
            'ratings_on_hrs' => (isset($this->ratings_on_hrs) ? $this->ratings_on_hrs : null),
            'total_number_of_ratings_on_hrs' => (isset($this->total_number_of_ratings_on_hrs) ? $this->total_number_of_ratings_on_hrs : null),
            'ranking_on_hrs' => (isset($this->ranking_on_hrs) ? $this->ranking_on_hrs : null),
            'badge_on_hrs' => (isset($this->badge_on_hrs) ? $this->badge_on_hrs : null),
//                                                                    'ratings_on_google' => (isset($this->dataArray['ratings_on_google) ? $this->dataArray['ratings_on_google : null),
//                                                                    'total_number_of_ratings_on_google' => (isset($this->dataArray['total_number_of_ratings_on_google) ? $this->dataArray['total_number_of_ratings_on_google : null),
            'policies' => $this->policies,
            'city' => $this->city,
            'city_id_on_hrs' => $this->city_id_on_hrs,
            'country_code' => $this->country_code,
            'latitude_hrs' => (isset($this->latitude) ? $this->latitude : null),
//                                                                    'latitude_google' => (isset($this->dataArray['google_latitude) ? $this->dataArray['google_latitude : null),
            'longitude_hrs' => (isset($this->longitude) ? $this->longitude : null),
//                                                                    'longitude_google' => (isset($this->dataArray['google_longitude) ? $this->dataArray['google_longitude : null),
            'hid' => $this->hid,
            'hotel_url_on_hrs' => (isset($this->url) ? $this->url : null),
//                                                                    'all_data_google' => (isset($this->dataArray['all_data_google) ? $this->dataArray['all_data_google : null),
            'source' => $this->source,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
 */
