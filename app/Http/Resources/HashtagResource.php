<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\EncryptDecryptTrait;

class HashtagResource extends JsonResource
{
    use EncryptDecryptTrait;
    public function toArray($request)
    {
        return
            [
                'id'    => $this->id,
                'title'    => $this->title,

            ];
    }
}
