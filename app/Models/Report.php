<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded=[];
    public function client()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }
    public function video()
    {
        return $this->belongsTo('App\Models\Video');
    }
}
