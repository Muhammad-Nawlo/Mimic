<?php

namespace App\Http\Resources;

use App\Traits\EncryptDecryptTrait;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LikeResource;
use App\Models\Like;
use App\Models\Replay;

class CommentResource extends JsonResource
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
                'id'        => $this->id,
                'client'        => new ClientResource($this->client) ?? null,
                'mentions'        => $this->mentions ?? null,
                'body'        => $this->body,
                'like_num'       => $this->like_num ?? 0,
                'replay_num'       => $this->replay_num ?? 0,
                'is_owner_comment'        => ((!empty(auth('client')->user()->id)) && ($this->clinet_id == auth('client')->user()->id)) ? true : false,
                'is_liked'        => (!empty(auth('client')->user()->id)) && (!empty(Like::where('comment_id', $this->id)->where('client_id', auth('client')->user()->id)->first())) ? true : false,
                'created_at'       => $this->created_at,
                'replay'       => !empty(Replay::where('comment_id', $this->id)->latest()->first()) ? new ReplayResource(Replay::where('comment_id', $this->id)->latest()->first()) : null,
            ];
    }
}
