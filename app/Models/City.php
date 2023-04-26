<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class City extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];
    protected $guarded = [];

    public function country(){
        return $this->belongsTo('App\Models\Country');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client','city_id');
    }
}
