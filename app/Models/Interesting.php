<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Video;

class Interesting extends Model
{
    public $fillable = ['name'];

    public function clients()
    {
        return $this->morphedByMany(Client::class, 'interestingable');
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'interestingable');
    }
}
