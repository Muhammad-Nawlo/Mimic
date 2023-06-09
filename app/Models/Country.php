<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Country extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['name'];
    protected $guarded = [];
    public function cities(){
        return $this->hasMany('App\Models\City');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client','country_id');
    }
}
