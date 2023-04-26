<?php

namespace App\Models;

use App\Interfaces\MustVerifyMobile as IMustVerifyMobile;
use App\Traits\MustVerifyMobile;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Client extends Authenticatable implements JWTSubject, IMustVerifyMobile
{
    use Notifiable, MustVerifyMobile;

    protected $guarded = [];
    protected $appended = ['image_path'];

    public function getImagePathAttribute()
    {
        return $this->image != null ? asset('uploads/clients/' . $this->image) : asset('uploads/clients/default.png');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function requsts()
    {
        return $this->hasMany('App\Models\Request');
    }

    public function client()
    {
        return $this->hasMany('App\Models\Client');
    }

    public function challenges()
    {
        return $this->hasMany('App\Models\Challenge', 'creater_id');
    }

    public function videos()
    {
        return $this->hasMany('App\Models\Video');
    }

    public function rank()
    {
        return $this->belongsTo('App\Models\Rank');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'type' => 'client',
        ];
    }

    public function winners()
    {
        return $this->hasMany('App\Models\Winer');
    }

    //likes and its count
    public function likes()
    {
        return $this->hasMany('App\Models\Like', 'video_id');
    }

    //comments and its count
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    //wtachs and its count
    public function watchs()
    {
        return $this->hasMany('App\Models\Watch');
    }

    //wtachs and its count
    public function shars()
    {
        return $this->hasMany('App\Models\Shar');
    }

    public function stories()
    {
        return $this->hasMany('App\Models\Story', 'client_id');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\Report', 'client_id');
    }

    public function interestings(): MorphToMany
    {
        return $this->morphToMany(Interesting::class, 'interestingable');
    }

    public function mentions()
    {
        return $this->belongsToMany(Video::class, 'mentions');
    }

    public function routeNotificationForNexmo($notification)
    {
        return $this->mobile_number;
    }
}
