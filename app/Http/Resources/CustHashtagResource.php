<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\EncryptDecryptTrait;

class CustHashtagResource extends JsonResource
{
    use EncryptDecryptTrait;
    public function toArray($request)
    {
        return
            [
                'title'    => $this->title,
            ];
    }
}
