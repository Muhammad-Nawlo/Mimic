<?php

namespace App\Models;

use App\Scopes\LimitPaginationScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Challenge extends Model
{
    protected $guarded = [];
    protected $appended = ['video_num', 'pending_video', 'accept_video', 'reject_video', 'shar_count', 'comment_count', 'like_count', 'who_join', 'video_thumb', 'watch_count'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'creater_id');
    }

    public function getVideoThumbAttribute()
    {
        return $this->thumb != null ? asset('videos/' . $this->thumb) : null;
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }


    public function videos()
    {
        return $this->hasMany('App\Models\Video');
    }

    //pending video
    public function getPendingVideoAttribute()
    {
        return $this->videos()->where('status', 'pending')->get();
    }

    //accept video
    public function getAcceptVideoAttribute()
    {
        return $this->videos()->where('status', 'accept')->get();
    }

    //reject video
    public function getRejectVideoAttribute()
    {
        return $this->videos()->where('status', 'reject')->get();
    }

    public function shares()
    {
        return $this->hasMany('App\Models\Shar');
    }

    public function getSharCountAttribute()
    {
        return $this->shares()->count() ?? 0;
    }

    //accept video
    public function getWhoJoinAttribute()
    {
        $clarr = array();
        if ($this->videos()->where('status', 'accept')->count() > 0) {
            foreach ($this->videos()->where('status', 'accept')->get() as $v) {
                array_push($clarr, $v->client_id);
            }
        }
        return $clarr;
    }

    public function getLikeCountAttribute()
    {
        $likeCount = 0;
        if ($this->videos()->where('status', 'accept')->count() > 0) {
            foreach ($this->videos()->where('status', 'accept')->get() as $v) {
                $likeCount += $v->like_num ?? 0;
            }
        }
        return $likeCount;
    }

    public function getWatchCountAttribute()
    {
        $watchCount = 0;
        if ($this->videos()->where('status', 'accept')->count() > 0) {
            foreach ($this->videos()->where('status', 'accept')->get() as $v) {
                $watchCount += $v->watch_num ?? 0;
            }
        }
        return $watchCount;
    }
}
