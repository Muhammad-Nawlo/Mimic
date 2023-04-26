<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $guarded=[];
    protected $appended=['video_path','thumb_path','react_num'];

    public function getVideoPathAttribute()
    {
        return $this->video != null ? asset('stories/'.$this->video) : null;
    }
    public function getThumbPathAttribute()
    {
        return $this->thumb != null ? asset('stories/'.$this->thumb) :  asset('stories/default.png');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
    public function watchs()
    {
        return $this->hasMany('App\Models\Watch');
    }
    public function reacts()
    {
        return $this->hasMany('App\Models\Like');
    }
    public function getReactNumAttribute()
    {
        return $this->reacts()->sum('react_num') ?? 0;
    }
}
