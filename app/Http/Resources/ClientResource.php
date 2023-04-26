<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\EncryptDecryptTrait;

class ClientResource extends JsonResource
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
            'user_name'      => $this->user_name,
            'email'      => $this->email,
            'mobile_number'      => $this->mobile_number ?? null,
            'country_name'      => $this->country->name ?? null,
            'city_name'      => $this->city->name ?? null,
            'image_path'      => $this->image_path,
            'rank_name'      => $this->rank->name ?? null,
            'date_of_birth'      => $this->date_of_birth,
            'code'      => $this->code,
            'challenge_num'      => $this->challenge_num ?? 0,
            'like_num'      => $this->like_num ?? 0,
            'video_num'      => $this->video_num ?? 0,
            'invite_num'      => $this->invite_num ?? 0,
        ];
    }
}
