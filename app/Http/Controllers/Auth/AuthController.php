<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth ;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $username = 'mobile';
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'max:255',
            'mobile' => 'required|regex:/^1[34578][0-9]{9}$/|digits:11|unique:users',
            'email' => 'email|max:255',
            'password' => 'required|min:6|max:255|confirmed',
            'code' => 'required|digits_between:4,10',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
    	$str=str_random(60);
        return User::create([
        	'name' => empty($data['name'])?'user_'.str_random():$data['name'],
        	'mobile' => $data['mobile'],
        	'email' => empty($data['email'])?null:$data['email'],
        	'password' => bcrypt($data['password']),
        	'api_token' => $str,
        ]);
    }
    


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apilogin(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'mobile' => 'required|regex:/^1[34578][0-9]{9}$/|digits:11',
    			'password' => 'required|min:6|max:255',
    	]);
        if ($validator->fails()) {
        	return  oapi_response($validator->errors()->all(),403);
        }
        $mobile=$request['mobile'];
        $password=$request['password'];
    	if (Auth::once(['mobile' =>$mobile , 'password' =>$password])) {
    		$user = Auth::user();
    		$user->api_token = str_random(60);
    		$user->save();
    		return  oapi_response([
    				//'msg'=>'login_success',
    				'user_id' => $user->id,
    				'api_token'=>$user->api_token
    		]);
    	}
    	return  oapi_response('password_incorrect',403);
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apiregister(Request $request)
    {
    	$validator = $this->validator($request->all());
    
    	if ($validator->fails()) {
    		if($validator->getMessageBag()->has('mobile')){
    			return oapi_response('手机号错误或手机号已注册',403);
    		}
        	return  oapi_response($validator->errors()->all(),400);
    	}
    	if(!(env('APP_DEBUG',false)&&$request->input('ignore_code'))){
	    	if(!verify_sms(['phone' => $request['mobile'], 'zone' => '86', 'code' => $request['code']] )){
	    		return  oapi_response('验证码错误',403);
	    	}
    	}
    
    	$user = $this->create($request->all());
    	return  oapi_response([
    				//'msg'=>'register_success',
    				'user_id' => $user->id,
    				'api_token'=>$user->api_token
    		]);
    	
    }
    
}
