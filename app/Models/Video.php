<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = [];
    protected $appended = ['thumb_path', 'watch_num', 'video_path', 'like_num', 'comment_num'];

    public function getVideoPathAttribute()
    {
        return $this->video != null ? asset('videos/' . $this->video) : null;
    }

    public function getThumbPathAttribute()
    {
        return $this->thumb != null ? asset('videos/' . $this->thumb) : asset('videos/default.png');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function challenge()
    {
        return $this->belongsTo('App\Models\Challenge', 'challenge_id');
    }


    //likes and its count
    public function likes()
    {
        return $this->hasMany('App\Models\Like', 'video_id');
    }

    public function getLikeNumAttribute()
    {
        return $this->likes()->count() ?? 0;
    }

    //comments and its count
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function getCommentNumAttribute()
    {
        return $this->comments()->count() ?? 0;
    }

    //wtachs and its count
    public function watchs()
    {
        return $this->hasMany('App\Models\Watch');
    }

    public function getWatchNumAttribute()
    {
        return $this->watchs()->count() ?? 0;
    }

    public function reasons()
    {
        return $this->hasMany('App\Models\Reason');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\Video', 'video_id');
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'mentions');
    }

    public function interestings()
    {
        return $this->morphToMany(Interesting::class, 'interestingable');
    }
}
