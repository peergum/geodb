<?php

namespace Peergum\GeoDB\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cc' => $this->cc,
            'cc2' => $this->cc2,
            'sqkm' => $this->sqkm,
            'population' => $this->population,
            'continent' => $this->continent,
            'tld' => $this->tld,
            'currency_code' => $this->currency_code,
            'currency_name' => $this->currency_name,
        ];
    }
}
