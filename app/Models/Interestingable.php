<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interestingable extends Model
{
    public $fillable = ['interesting_id', 'interestingable_id', 'interestingable_type'];
}
