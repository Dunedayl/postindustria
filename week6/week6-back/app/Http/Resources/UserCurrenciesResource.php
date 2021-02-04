<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCurrenciesResource extends JsonResource
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
            'currency' => $this->currency,
            'force_exchange' => $this->force_exchange,
            'force_exchange_amount' => $this->force_exchange_amount,
        ];
    }
}
