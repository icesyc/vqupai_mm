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
		return isset($json['errcode']) ? false : $json;
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
		return isset($json['errcode']) ? false : $json;
	}
}