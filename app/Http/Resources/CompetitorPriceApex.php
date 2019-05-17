<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompetitorPriceApex extends JsonResource
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
            'xAxis' => $this->xAxis,
            'yAxis' => $this->yAxis,
            'rooms' => $this->rooms
        ];
    }
}
