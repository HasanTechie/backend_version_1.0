<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'venue_name' => $this->venue_name,
            'venue_address' => $this->venue_address,
            'event_date' => $this->event_date,
            'url' => $this->url,
            'min_price' => $this->standard_price_including_fees_min,
            'max_price' => $this->standard_price_including_fees_max,
            'all_data' => $this->all_data,
        ];
    }
}
