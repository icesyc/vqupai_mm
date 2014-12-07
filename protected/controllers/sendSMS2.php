<?
/*--------------------------------
功能:		短信通 HTTP接口 发送短信
修改日期:	2012-04-08
说明:		http://sms.smstown.com/web/?uid=用户账号&pwd=密码&mobile=号码&content=内容
状态:
	100 发送成功
	zt=101 uid不存在
	zt=102 MD5密码出错
	zt=103短信数量不足
	zt=104帐号不正常
	zt=105 手机号码出错
	zt=106 内容为空
--------------------------------*/
function doSMS($mobile,$content) {
	if(!$mobile or !$content) {
		return false;
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://sms-api.luosimao.com/v1/send.json");

	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	curl_setopt($ch, CURLOPT_HEADER, FALSE);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, TRUE); 
	curl_setopt($ch, CURLOPT_SSLVERSION , 3);

	curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-bc616bcbf4e369f1566ad0fa7ef9bc76');


	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobile,'message' => $content));

	$res = curl_exec( $ch );
	curl_close( $ch );
	//$res  = curl_error( $ch );
	//var_dump($res);
	$re = json_decode($res, true);
	if($re['error'] == 0) {
		return true;
	}
	else {
		print_r($re);
		return false;
	}
}

//doSMS('18600053550','您的验证码是 678901 【微趣拍】');
?>