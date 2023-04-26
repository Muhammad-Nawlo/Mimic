<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replay extends Model
{
    protected $guarded=[];
    protected $appended=['like_num'];
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
         return $this->hasMany('App\Models\Like','replay_id');
     }
     public function getLikeNumAttribute()
     {
        return $this->likes()->count() ?? 0;
     }
}
