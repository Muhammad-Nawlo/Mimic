<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Notification extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['title','body'];
    protected $guarded=[];
    public function sender()
    {
        return $this->belongsTo('App\Models\Client','sender_id');
    }
    public function reciver()
    {
        return $this->belongsTo('App\Models\Client','reciver_id');
    }

    public function comment()
    {
        return $this->belongsTo('App\Models\Comment','comment_id');
    }
    public function video()
    {
        return $this->belongsTo('App\Models\Video','video_id');
    }
    public function challenge()
    {
        return $this->belongsTo('App\Models\Challenge','challenge_id');
    }
    public function replay()
    {
        return $this->belongsTo('App\Models\Replay','replay_id');
    }
}
