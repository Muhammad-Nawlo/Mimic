<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;
use App\Models\Favourite;



class User extends Authenticatable
{
    use Notifiable, LaratrustUserTrait, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $append = ['image_path'];

    public function getImagePathAttribute(){
        return $this->image != null ? asset('uploads/users_images/'.$this->image) :  asset('uploads/users_images/default.png') ;
    }


    public function ScopeAdmin($query)
    {
        return $query->where('type', 'admin');
    }
    public function ScopeEmployee($query)
    {
        return $query->where('type', 'emp');
    }
    public function ScopeDelivery($query)
    {
        return $query->where('type', 'delivery');
    }

    public function scopeStudent()
    {
        return $this->where('type', 2);
    }


    public function ScopeUser($query)
    {
        return $query->where('type', 'user');
    }

    public function ScopeDeliveryActive($query)
    {
        return $query->where('type', 2)->where('delivery_status', 1);
    }

}
