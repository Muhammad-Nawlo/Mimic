<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\EncryptDecryptTrait;

class RankResource extends JsonResource
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
                'id'      => $this->id,
                'title'        => $this->title,
                'image_path'        => $this->image_path,
                'challenge_num'        => $this->challenge_num,
                'like_num'        => $this->like_num,
                'video_num'     => $this->video_num,
                'invitation_num'     => $this->invit_num,
                'is_ranked'     => (!empty(auth('client')->user())) && (auth('client')->user()->rank_id == $this->id) ? true : false
            ];
    }
}
