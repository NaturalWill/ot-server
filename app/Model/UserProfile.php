<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name', 'sex', 'nickname', 'org_created_num', 'org_joined_num', 'user_id'
    ];
    

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';
}
