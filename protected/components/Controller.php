<?php
/**
 * 控制器基类
 */

class Controller extends CController
{
	public $layout = false;

	public $loginRequired  = false;


	//微信登录
	public function wechatLogin(){
		Yii::import('application.extensions.wechat.Wechat');
		Yii::app()->session->open();
		$user = Yii::app()->session['user'];
		//$user = User::model()->findByPk(100001);
		if($user){
			return $user;
		}

		$wechat = new Wechat();
		$code = $this->getString('code');
		//第一次请求授权
		if(!$code){
			$config['redirectUri'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&first=1';
			$wechat = new Wechat($config);
			$wechat->authorize(null, 'snsapi_base');
			Yii::app()->end();
		}

		//第一次授权回调
		if($this->getInt('first')){
			$openArr = $wechat->getOpenId($code);
			if(!$openArr){
				$this->error(1301, '获取微信授权信息失败');
			}
			$condition['platform'] = UserPlatformBind::PLAT_WECHAT_MP;
			$condition['open_id'] = $openArr['openid'];
			$platform = UserPlatformBind::model()->findByAttributes($condition);
			//没有查到对应的open_id, 请求二次授权，获取用户资料
			if(!$platform){
				$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
				parse_str($_SERVER['QUERY_STRING'], $query);
				unset($query['code'], $query['first'], $query['state']);
				$url .= '?' . http_build_query($query);
				$config['redirectUri'] = $url;
				$wechat = new Wechat($config);
				$wechat->authorize(null, 'snsapi_userinfo');
				Yii::app()->end();
			}else{
				//有open_id,直接登录
				$user = User::model()->findByPk($platform->uid);
				Yii::app()->session['user'] = $user;
				return $user;
			}
		}
		//二次授权的回调走下面的流程
		$openArr = $wechat->getOpenId($code);
		$profile = $wechat->getUserInfo($openArr['access_token'], $openArr['openid']);
		if(!$openArr || !$profile){
			$this->error(1302, '获取微信授权信息失败');
		}
		
		//首次登录保存用户信息，这里保存unionid
		$platform = UserPlatformBind::syncWechatMpUser($openArr['access_token'], $openArr['openid'], $profile['unionid'], $profile);
		if(!$platform){
			$this->error(1303, '同步微信用户信息失败');
		}

		//登录
		$user = User::model()->findByPk($platform->uid);
		Yii::app()->session['user'] = $user;
		return $user;
	}

	public function renderJSON($data){
		header('Content-type: application/json');
    	echo json_encode($data);
    	Yii::app()->end();
	}

	public function error($code, $msg=''){
		$data['code'] = $code;
		if(strlen($msg) > 0){
			$data['msg'] = $msg;
		}
		$this->renderJSON($data);
	}

	public function success($data=array()){
		$data['success'] = true;
		$this->renderJSON($data);
	}

	public function modelListToArray($list, $flat=false){
		$res = array();
		foreach($list as $model){
			$res[] = $this->modelToArray($model, $flat);
		}
		return $res;
	}

	/**
	 * 将activeRecord转换成array, 同时处理关联的ar对象
	 *
	 * @param $model activeRecord对象
	 */
	public function modelToArray($model, $flat=false){
		$notNull = function($v){
			return $v !== null;
		};
		$data = array_filter($model->attributes, $notNull);
		$relations = array_keys($model->relations());
		foreach($relations as $rel){
			if($model->hasRelated($rel)){
				$related = $model->getRelated($rel);
				$relData = array_filter($related->attributes, $notNull);
				if($flat){
					$data = $data + $relData;
				}else{
					$data[$rel] = $relData;
				}
			}
		}
		return $data;
	}

	//返回一个ar数组中某一列的值
	public function getModelColumn($list, $key='id'){
		$res = array();
		foreach($list as $ar){
			$res[] = $ar->$key;
		}
		return array_unique($res);
	}

	//把ar数组按主键做hash
	public function modelToHash($list, $key){
		$res = array();
		foreach($list as $model){
			$res[$model->$key] = $model;
		}
		return $res;
	}

	//获取参数的便捷函数
	public function postInt($key, $default=0){
		return isset($_POST[$key]) ? intval($_POST[$key]) : $default;
	}

	public function postString($key, $default=''){
		return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
	}

	public function getInt($key, $default=0){
		return isset($_GET[$key]) ? intval($_GET[$key]) : $default;
	}

	public function getString($key, $default=''){
		return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
	}

	//与app交互，调用app对应的方法并传递参数
	public function notifyApp($data){
		$json = json_encode($data);
		$js = "<script type=\"text/javascript\">
			window.json = $json;
			if(top != self) top.postMessage(json, '*');
		</script>";
		echo $js;
		Yii::app()->end();
	}

	public function formatTime($secs){
		$a = array(
	        3600 => '小时',
	        60 => '分钟',
	     );
		$r = '';
	    foreach ($a as $k => $str){
	        $d = $secs / $k;
	        $secs = $secs % $k;
	        if ($d >= 1){
	            $r .= floor($d) . $str;
	        }
	    }
	    return $r;
	}

	public function getImageBySize($path, $size){
		$basename = basename($path);
		$dirname = dirname($path);
		return $dirname . "/" .  $size . "." . $basename;
	}

	/**
	 * 将时间戳转换成多少时间前的字符串
	 */
	public function timeAgo($ptime){
	    $etime = time() - $ptime;
	    if ($etime < 60){
	        return '刚刚';
	    }
	    $a = array(
	    	12 * 30 * 24 * 60 * 60  =>  '年前',
	        30 * 24 * 60 * 60       =>  '个月前',
	        24 * 60 * 60            =>  '天前',
	        60 * 60                 =>  '小时前',
	        60                      =>  '分钟前',
	     );
	    foreach ($a as $secs => $str){
	        $d = $etime / $secs;
	        if ($d >= 1){
	            $r = floor($d);
	            return $r . $str;
	        }
	    }
	}
}
