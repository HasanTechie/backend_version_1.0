<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompetitorRoomAvgPrice extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'price' => $this->price,
            'check_in_date' => $this->check_in_date,
            'request_date' => $this->request_date,
//            'check_out_date' => $this->check_out_date,
            'competitors_data' => $this->competitors,
        ];
    }
}
