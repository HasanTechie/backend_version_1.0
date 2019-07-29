<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProcessedCSVs extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'user_id' => $this->user_id,
          'file_name' => $this->file_name,
          'file_url' => $this->file_url,
          'file_type' => $this->file_type,
          'uploaded_by' => $this->uploaded_by,
        ];
    }
}
