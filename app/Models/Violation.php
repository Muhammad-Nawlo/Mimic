<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $guarded=[];
    public function video()
    {
        return $this->belongsTo('App\Models\Video');
    }
}
