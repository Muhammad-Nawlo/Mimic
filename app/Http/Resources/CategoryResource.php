<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\EncryptDecryptTrait;

class CategoryResource extends JsonResource
{
    use EncryptDecryptTrait;
    public function toArray($request)
    {
        return
            [
                'id'    => $this->id,
                'name'    => $this->name,
                'description'    => $this->description,
                'image_path'    => $this->image_path,
            ];
    }
}
