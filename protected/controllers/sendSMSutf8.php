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
	date_default_timezone_set('Asia/Shanghai');
	$date = date('Y-m-d H:i:s', time());
	$uid = '1853';		//用户账号
	$pwd = 'fe!lG0od';		//密码
	//$mobile	 = '18600053550,13621098806,13522531922';	//号码
	//$mobile	 = '18600053550';	//号码
	//$content = '微趣拍短信通知：'.$date;		//内容
	//即时发送
	$res = sendSMS($uid,$pwd,$mobile,$content);
	return $res;
	//echo $res;
	//定时发送
	//$time = '2010-05-27 12:11';
	//$res = sendSMS($uid,$pwd,$mobile,$content,$time);
	//echo $res;
}

function sendSMS($uid,$pwd,$mobile,$content,$time='',$mid='')
{
	//$http = 'http://sms.smstown.com/webutf8/';
	$http = 'http://api.smstown.com/';
	//$http = 'http://sms.smstown.com/web/';
	$data = array
		(
		'uid'=>$uid,					//用户账号
		'pwd'=>strtolower(md5($pwd)),   //MD5位32密码
		'mobile'=>$mobile,				//号码
		'content'=>$content,			//内容
		'endode'=>'utf8',
		//'time'=>$time,		//定时发送
		'mid'=>$mid						//子扩展号
		);
	$re= postSMS($http,$data);			//POST方式提交
	//return $re;
	if( trim($re) == '100' )
	{
		return true;
	}
	else 
	{
		return false;
	}
}

function postSMS($url,$data='')
{
	$row = parse_url($url);
	$host = $row['host'];
	$port = $row['port'] ? $row['port']:80;
	$file = $row['path'];
	while (list($k,$v) = each($data)) 
	{
		$post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
	}
	$post = substr( $post , 0 , -1 );
	$len = strlen($post);
	$fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
	if (!$fp) {
		return "$errstr ($errno)\n";
	} else {
		$receive = '';
		$out = "POST $file HTTP/1.1\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Content-type: application/x-www-form-urlencoded\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Content-Length: $len\r\n\r\n";
		$out .= $post;		
		fwrite($fp, $out);
		while (!feof($fp)) {
			$receive .= fgets($fp, 128);
		}
		fclose($fp);
		$receive = explode("\r\n\r\n",$receive);
		unset($receive[0]);
		return implode("",$receive);
	}
}
?>