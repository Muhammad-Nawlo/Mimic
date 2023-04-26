<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $guarded=[];
    public function sender()
    {
        return $this->belongsTo(Client::class,'sender_id');
    }
    public function reciver()
    {
        return $this->belongsTo(Client::class,'reciver_id');
    }
}
