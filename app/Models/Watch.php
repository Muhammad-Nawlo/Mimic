<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watch extends Model
{
    protected $guarded=[];
    protected $table='watchs';

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function video()
    {
        return $this->belongsTo('App\Models\Video');
    }
    
    public function story()
    {
        return $this->belongsTo('App\Models\Story');
    }
}
