<?php



if (! function_exists('oapi_response')) {
	/**
	 * Return a oapi response from the application.
	 *
	 * @param array $msg 错误消息
	 * @param int $code 错误代码，0为正常
	 * @param array $data 返回数据
	 * @return string 请求结果
	 */	
	function oapi_response($data, $code = 200) {
		return response()->json($data,$code)->setCallback(request()->input('callback'));
		
	}
}
if (! function_exists('verify_sms')) {
	/**
	 * 发起一个post请求到指定接口
	 * http://wiki.mob.com/webapi2-0/
	 *
	 * @param array $params post参数
	 * ['phone' => '152xxxx4345', 'zone' => '86', 'code' => '1234']
	 * @param int $timeout 超时时间
	 * @return string 请求结果
	 */	
	function verify_sms(  array $params = array(), $timeout = 30 ) {
		$appkey=env('MOB_API_KEY',false);
		if(!$appkey){
			return false;
		}
		$params['appkey'] = $appkey;
		$result = postRequest(env('MOB_API_URL'),$params,$timeout);
		$json=json_decode($result);
		if($json->status==200){
			return true;
		}
		return false;
	}
}

if (! function_exists('postRequest')) {
	/**
	 * 发起一个post请求到指定接口
	 * http://wiki.mob.com/webapi2-0/
	 *
	 * @param string $api 请求的接口
	 * @param array $params post参数
	 * @param int $timeout 超时时间
	 * @return string 请求结果
	 */
	function postRequest( $api, array $params = array(), $timeout = 30 ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $api );
		// 以返回的形式接收信息
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		// 设置为POST方式
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
		// 不验证https证书
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
			'Accept: application/json',
		) );
		// 发送数据
		$response = curl_exec( $ch );
		// 不要忘记释放资源
		curl_close( $ch );
		return $response;
	}
}