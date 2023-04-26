<?php

namespace App\Http\Resources;

use App\Traits\EncryptDecryptTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Like;

class ReplayResource extends JsonResource
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
                'client'      => new ClientResource($this->client) ?? null,
                'body'      => $this->body,
                'like_num'     => $this->like_num ?? 0,
                'mentions'      => $this->mentions ?? null,
                'is_owner_replay'     => (!empty(auth('client')->user()->id)) && ($this->clinet_id == auth('client')->user()->id) ? true : false,
                'is_liked'     => (!empty(auth('client')->user()->id)) && (!empty(Like::where('replay_id', $this->id)->where('client_id', auth('client')->user()->id)->first())) ? true : false,
                'created_at'     => $this->created_at,
            ];
    }
}
