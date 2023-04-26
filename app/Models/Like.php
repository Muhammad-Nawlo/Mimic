<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $guarded=[];

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function video()
    {
        return $this->belongsTo('App\Models\Video');
    }

    public function comment()
    {
        return $this->belongsTo('App\Models\Comment');
    }

    public function replay()
    {
        return $this->belongsTo('App\Models\Replay');
    }
    
    public function story()
    {
        return $this->belongsTo('App\Models\Story');
    }
}
