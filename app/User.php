<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name', 'mobile', 'password', 'email', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'mobile', 'password', 'api_token', 'remember_token',
    ];
    
    public function profile()
    {
    	return $this->hasOne('App\Model\UserProfile');
    }
    
}
