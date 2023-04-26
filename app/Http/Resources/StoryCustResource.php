<?php

namespace App\Http\Resources;

use App\Traits\EncryptDecryptTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryCustResource extends JsonResource
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
        return
            [
                'id'            => $this->client->id,
                'image_path'            => $this->client->image_path,
                'reaction_num'            => $this->react_num,
            ];
    }
}
