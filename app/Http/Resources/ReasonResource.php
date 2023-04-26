<?php

namespace App\Http\Resources;

use  App\Http\Resources\stageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\EncryptDecryptTrait;

class ReasonResource extends JsonResource
{
    use EncryptDecryptTrait;

    public function toArray($request)
    {
        return [

            'id'      => $this->id,
            'reason'      => $this->reason,
            'created_at'     => $this->created_at,
        ];
    }
}
