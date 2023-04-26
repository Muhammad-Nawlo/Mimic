<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Rank extends Model implements TranslatableContract
{
    use Translatable;
    public $translatedAttributes = ['title'];
    protected $guarded=[];
    protected $appended=['image_path'];
    public function getImagePathAttribute()
    {
        return $this->image != null ? asset('uploads/ranks/'.$this->image) :  asset('uploads/ranks/default.png');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client','rank_id');
    }
}
