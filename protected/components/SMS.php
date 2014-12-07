<?php
/**
 * 发送短信程序
 */
class SMS{

	/**
	 * @param string $mobile 手机号
	 * @param string $content 短信内容
	 */
	public static function send($mobile, $content){
		if(!$mobile or !$content) {
			return false;
		}
		$content .= '【微趣拍】';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://sms-api.luosimao.com/v1/send.json");

		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-bc616bcbf4e369f1566ad0fa7ef9bc76');

		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobile,'message' => $content));

		$res = curl_exec( $ch );
		curl_close( $ch );
		$re = json_decode($res, true);
		if($re && $re['error'] == 0) {
			return true;
		}
		else {
			return false;
		}
	}
}