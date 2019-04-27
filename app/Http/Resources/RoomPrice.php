<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomPrice extends JsonResource
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
            'room_id' => $this->id,
            'price' => $this->price,
            'room' => $this->room,
            'hotel_name' => $this->hotel_name,
            'hotel_id' => $this->hotel_id,
            'check_in_date' => $this->check_in_date,

        ];
    }
}

/*            'currency' => $this->currency,
            'photo' => $this->photo,
            'hotel_uid' => $this->hotel_uid,
            'hotel_eurobooking_id' => $this->hotel_eurobooking_id,
            'number_of_adults_in_room_request' => $this->number_of_adults_in_room_request,
            'check_out_date' => $this->check_out_date,
            'rid' => $this->rid,
            'request_date' => $this->request_date,
            'source' => $this->source,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,*/
