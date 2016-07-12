<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\UserProfile;
class ProfileController extends Controller
{
    // 查看个人资料
    public function index(){
    	$user=Auth::guard('api')->user()->profile()->first();
    	return oapi_response($user);
    }
    // 修改个人资料
    public function store(){
    	$user=Auth::guard('api')->user()->profile()->first();
    	
    	if(empty($user)){

    		UserProfile::firstOrCreate(['user_id' => Auth::guard('api')->id(),
    				'nickname' => 'user_'.str_random()
    		]);
    		$user=Auth::guard('api')->user()->profile()->first();
    	}
    	if(!empty($nickname=request()->input('nickname'))){
    		$user->nickname=$nickname;
    	}
    	if(!empty($name=request()->input('name'))&&!empty($user->name)){
    		$user->name=$name;
    	}
    	if(in_array($sex=request()->input('sex'), ['female','male'])){
    		$user->sex=$sex;
    	}
    	$user->save();

    	return oapi_response($user);
    }
}
