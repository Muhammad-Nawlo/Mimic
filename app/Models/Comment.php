<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded=[];
    protected $appended=['replay_num','like_num'];

    public function mention()
    {
        return $this->belongsTo('App\Models\Client','mention_id');
    }
    public function client()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }
    public function video()
    {
        return $this->belongsTo('App\Models\Video');
    }

    //likes and its count
    public function likes()
    {
        return $this->hasMany('App\Models\Like','comment_id');
    }
    public function getLikeNumAttribute()
    {
        return $this->likes()->count() ?? 0;
    }
    //replies and its count
    public function replies()
    {
        return $this->hasMany('App\Models\Replay');
    }
    public function getReplayNumAttribute()
    {
        return $this->replies()->count() ?? 0;
    }
}
