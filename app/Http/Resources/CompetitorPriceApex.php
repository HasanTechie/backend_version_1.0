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
            'rooms' => $this->rooms,
            'xAxis' => $this->xAxis,
            'yAxis' => $this->yAxis,
            'dataTable' => $this->dataTable
        ];
    }
}
