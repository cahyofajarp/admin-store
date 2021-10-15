<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;	
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable,HasApiTokens;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getImageAttribute($image)
    {   
        if($image != null){
            return asset('storage/' . $image);
        }
    }

    //   /**
    //  * getJWTIdentifier
    //  *
    //  * @return void
    //  */
    // public function getJWTIdentifier()
    // {
    //     return $this->getKey();
    // }
        
    // /**
    //  * getJWTCustomClaims
    //  *
    //  * @return void
    //  */
    // public function getJWTCustomClaims()
    // {
    //     return [];
    // }

}
