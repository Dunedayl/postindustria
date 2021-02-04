<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserActionResource extends JsonResource
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
            'sum' => number_format ($this->sum / 100, 2),
            'currency' => $this->currency,
            'date' => $this->date,
            'info' => $this->info,
            'action' => $this->action,
        ];
    }
}
