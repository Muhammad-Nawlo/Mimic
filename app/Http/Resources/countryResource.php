<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\EncryptDecryptTrait;

class CountryResource extends JsonResource
{
    use EncryptDecryptTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'name'      => $this->name,
            'city'     => CityResource::collection($this->cities),
        ];
    }
}
