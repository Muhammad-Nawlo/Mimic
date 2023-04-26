<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\EncryptDecryptTrait;

class ClientCustomResource extends JsonResource
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
            'image_path'      => $this->image_path,
        ];
    }
}
