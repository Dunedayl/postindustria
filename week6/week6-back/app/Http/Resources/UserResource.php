<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'tax_rate' => $this->tax_rate,
            'notification_period' => $this->notification_period,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'image' => $this->image,
            'default_currency' => $this->default_currency,
            'currencies' => UserCurrenciesResource::collection($this->currencies)
        ];
    }
}
