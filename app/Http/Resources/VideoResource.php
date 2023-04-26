<?php

namespace App\Http\Resources;

use App\Traits\EncryptDecryptTrait;

use App\Models\Like;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    use EncryptDecryptTrait;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [
                'id' => $this->id,
                'description' => $this->description,
                'client' => new ClientResource($this->client) ?? null,
                'created_at' => $this->created_at,
                'video' => $this->video != null ? $this->video_path : null,
                'thumb_path' => $this->thumb_path,
                'is_owner_video' => ((!empty(auth('client')->user()->id)) && ($this->clinet_id == auth('client')->user()->id)) ? true : false,
                'is_liked' => (!empty(auth('client')->user()->id)) && (!empty(Like::where('video_id', $this->id)->where('client_id', auth('client')->user()->id)->first())) ? true : false,
                'comment_num' => $this->comment_num ?? 0,
                'like_num' => $this->like_num ?? 0,
                'watch_num' => $this->watch_num ?? 0,
                'reason' => ReasonResource::collection($this->reasons) ?? null,
                'interesting' => InterestingResource::collection($this->whenLoaded('interestings')) ?? null,
                'clients' => ClientResource::collection($this->whenLoaded('clients')) ?? null,
            ];
    }
}
