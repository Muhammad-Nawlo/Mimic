<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\EncryptDecryptTrait;

class notificationResource extends JsonResource
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
        return [
            'id'            => $this->id,
            'title'            => $this->title,
            'body'            => strip_tags($this->body),
            'type'           => $this->type,
            'status'           => $this->status,
            'read_at'            => $this->read_at != null ? true : false,
            'created_at'             => date('Y-m-d h:i:s', strtotime($this->created_at)),
            'client'             => new ClientCustomResource($this->sender) ?? null,
            'comment_id'             => $this->comment_id ?? null,
            'replay_id'            => $this->replay_id ?? null,
            'video'             => new VideoCustomResource($this->video) ?? null,
            'challenge_id'             => $this->challenge_id ?? null,

        ];
    }
}
