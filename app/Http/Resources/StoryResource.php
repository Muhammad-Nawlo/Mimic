<?php

namespace App\Http\Resources;

use App\Traits\EncryptDecryptTrait;
use App\Models\Watch;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
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
                'client'            => new ClientResource($this->client) ?? null,
                'video'            => $this->video != null ? $this->video_path : null,
                'thumb_path'            => $this->thumb_path,
                'is_owner_story'           => (!empty(auth('client')->user()->id)) && ($this->clinet_id == auth('client')->user()->id) ? true : false,
                'created_at'           => date('Y-m-d h:i', strtotime($this->created_at)),
                'watch_count'            => $this->watchs_count,
                'reaction_num'           => $this->react_num,
                'is_watched'            => (!empty(auth('client')->user()->id)) && (!empty(Watch::where('story_id', $this->id)->where('client_id', auth('client')->user()->id)->first())) ? true : false,

            ];
    }
}
