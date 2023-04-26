<?php

namespace App\Http\Resources;

use App\Traits\EncryptDecryptTrait;

use App\Models\Like;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoCustomResource extends JsonResource
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
                'id'            => $this->id,
                'video'            => $this->video != null ? $this->video_path : null,
                'thumb_path'            => $this->thumb_path,
            ];
    }
}
