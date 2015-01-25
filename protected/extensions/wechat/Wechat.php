<?php
/**
 * 实现微信公众号OAuth2.0授权逻辑
 *
 * $wechat = new Wechat;
 * 鉴权
 * $wechat->authorize();
 *
 * $arr = $wechat->getOpenId($code);
 * var_dump($openId['openid']);
 * $user = $wechat->getUserInfo($arr['access_token'], $arr['openid']);
 * var_dump($user);
 *
 * @author icesyc 2014/07/15
 */

class Wechat {
	
	private $authUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';
	private $apiUrl = "https://api.weixin.qq.com/sns/oauth2/";
	private $redirectUri = 'http://www.vqupai.com/wechat/?r=userAuction&id=1';
	private $appId = 'wx0d6e23dc148c5034';
	private $appKey = '32cd3d6accfeff6490dfdd8b7c0b8882';

	const CACHE_ACCESS_TOKEN = "mp.wechat.access_token";
	const CACHE_TIKET = "mp.wechat.jsticket";

	public $lastError;

	public function __construct($config=array()){
		isset($config['appId']) && $this->appId = $config['appId'];
		isset($config['appKey']) && $this->appKey = $config['appKey'];
		isset($config['redirectUri']) && $this->redirectUri = $config['redirectUri'];
	}

	/**
	 * 第一步，跳转到授权页，用户登录成功后获得authorization code
	 *
	 * @param string $state 用于回调后保存的状态值，可以是做任意值
	 * @param string $scope snsapi_base|snsapi_userinfo snsapi_userinfo需要用户授权
	 */
	public function authorize($state=null, $scope='snsapi_base'){
		$param['appid'] = $this->appId;
		$state && $param['state'] = $state;
		$param['redirect_uri'] = $this->redirectUri;
		$param['response_type'] = 'code';
		$param['scope'] = $scope;
		$url = $this->authUrl . '?' . http_build_query($param) . '#wechat_redirect';
		header('Location: ' . $url);
		return;
	}

	//第二步，根据authorization code获取accessToken及open_id
	/*
	 *  {
	 *	   "access_token":"ACCESS_TOKEN",
	 *	   "expires_in":7200,
	 *	   "refresh_token":"REFRESH_TOKEN",
	 *	   "openid":"OPENID",
	 *	   "scope":"SCOPE"
	 *  }
	 */
	public function getOpenId($code){
		$param['grant_type'] = 'authorization_code';
		$param['appid'] = $this->appId;
		$param['secret'] = $this->appKey;
		$param['code'] = $code;
		$url = $this->apiUrl . 'access_token?' . http_build_query($param);
		$json = HttpClient::get($url);
		if(!$json) return false;
		$json = json_decode($json, true);
		if(isset($json['errcode']) && $json['errcode'] > 0){
			$this->lastError = $json['errmsg'];
			return false;
		}
		return $json;
	}

	//第三步，根据openId和token获取用户信息
	/*
		{
		   "openid":" OPENID",
		   " nickname": NICKNAME,
		   "sex":"1",
		   "province":"PROVINCE"
		   "city":"CITY",
		   "country":"COUNTRY",
		    "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46", 
			"privilege":[
			"PRIVILEGE1"
			"PRIVILEGE2"
		    ]
		}
	*/
	public function getUserInfo($token, $openId){
		$param['openid'] = $openId;
		$param['access_token'] = $token;
		$param['lang'] = 'zh_CN';
		$url = 'https://api.weixin.qq.com/sns/userinfo?' . http_build_query($param);
		$json = HttpClient::get($url);
		if(!$json) return false;
		$json = json_decode($json, true);
		if(isset($json['errcode']) && $json['errcode'] > 0){
			$this->lastError = $json['errmsg'];
			return false;
		}
		return $json;
	}

	/**
	 * 获取jsticket
	 *
	 * @param string $token access token
	 * @return 
	 */
	public function getTicket($token=null){
		$cache = $this->getCache(self::CACHE_TIKET);
		if($cache){
			return $cache['ticket'];
		}
		$token = $token ?: $this->getAccessToken();
		if(!$token){
			return false;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?';
		$param['access_token'] = $token;
		$param['type'] = 'jsapi';
		$url = $url . http_build_query($param);
		$json = HttpClient::get($url);
		if(!$json){
			return false;	
		}
		$json = json_decode($json, true);
		if(isset($json['errcode']) && $json['errcode'] > 0){
			$this->lastError = $json['errmsg'];
			return false;
		}
		$this->setCache(self::CACHE_TIKET, $json);
		return $json['ticket'];
	}

	/**
	 * 公众平台获取全局access_token
	 *
	 */
	public function getAccessToken(){
		$cache = $this->getCache(self::CACHE_ACCESS_TOKEN);
		if($cache){
			return $cache['access_token'];
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/token?';
		$param['grant_type'] = 'client_credential';
		$param['appid'] = $this->appId;
		$param['secret'] = $this->appKey;
		$url = $url . http_build_query($param);
		$json = HttpClient::get($url);
		if(!$json) return false;
		$json = json_decode($json, true);
		if(isset($json['errcode']) && $json['errcode'] > 0){
			$this->lastError = $json['errmsg'];
			return false;
		}
		$this->setCache(self::CACHE_ACCESS_TOKEN, $json);
		return $json['access_token'];
	}

	//对url进行签名
	public function sign($ticket, $url){
		$param['jsapi_ticket'] = $ticket;
		$param['timestamp'] = time();
		$param['noncestr'] = uniqid();
		$param['url'] = $url;
		ksort($param);
		$str = urldecode(http_build_query($param));
		$param['signature'] = sha1($str);
		$param['appId'] = $this->appId;
		$param['nonceStr'] = $param['noncestr'];
		unset($param['jsapi_ticket'], $param['url'], $param['noncestr']);
		return $param;
	}

	//获取缓存的access_token数据
	public function getCache($file){

		$file = sys_get_temp_dir() . "/" . $file;
		if(!file_exists($file)){
			return false;
		}
		$json = json_decode(file_get_contents($file), true);
		if(!$json){
			return false;
		}
		if(time() - filemtime($file) > $json['expires_in']){
			return false;
		}
		return $json;
	}

	public function setCache($file, $data){
		$file = sys_get_temp_dir() . "/" . $file;
		$data = json_encode($data);
		return file_put_contents($file, $data);
	}
}