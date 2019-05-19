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
