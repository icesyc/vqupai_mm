<?php

/**
 * This is the model class for table "user_platform_bind".
 *
 * The followings are the available columns in table 'user_platform_bind':
 * @property string $id
 * @property string $uid
 * @property string $platform
 * @property string $token
 */
class UserPlatformBind extends CActiveRecord
{
	//平台类型
	const PLAT_QQ = 'qq';
	const PLAT_WB = 'wb';
	const PLAT_WECHAT = 'wechat';
	const PLAT_WECHAT_MP = 'wechat_mp';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_platform_bind';
	}
	
	public function relations(){
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'uid', 'joinType' => 'inner join', 'select' => 'id,nick,avatar')
		);
	}

	//处理用户登录，并返回需要输出给客户端的data
	public function userLogin($uid=null){
		!$uid && $uid = $this->uid;
		$user = User::model()->findByPk($uid);
		$idn = new AppUserIdentity($user->uname, $user->passwd);
		$idn->setId($uid);
		$idn->setName($user->uname);
		$idn->setPersistentStates($user->attributes);
		Yii::app()->user->login($idn);

		$data['code'] = 0;
		$data['token'] = Yii::app()->getSession()->sessionID;
		foreach($user as $k => $v){
			if(in_array($k, User::$noCacheField)) continue;
			$data[$k] = $v;
		}
		
		//需要缓存的数据
		$res['user_data'] = User::model()->getCacheData($data['id']);
		$res['user'] = $data;
		return $res;
	}

	public static function syncUser($token, $openId, $attributes, $type, $platInfo=array()){
		$trans = Yii::app()->db->beginTransaction();
		try{
			$user = new User;
			foreach($attributes as $attr => $value){
				$user->$attr = $value;
			}
			$user->gender = 1;
			$user->reg_time = time();
			$user->insert();
			//更新用户名
			$user->uname = $type . $user->id;
			$user->update(array('uname'));

			$platform = new UserPlatformBind;
			$platform->uid = $user->id;
			$platform->platform = $type;
			$platform->open_id = $openId;
			$platform->token = $token;
			//其它需要保存的平台相关数据
			foreach($platInfo as $f => $value){
				$platform->$f = $value;
			}
			$platform->insert();
			$trans->commit();
			$user->onRegisterSuccess();
			return  $platform;
		}catch(Exception $e){
			Yii::log($e->getMessage(), 'error', 'platform');
			$trans->rollback();
			return false;
		}
	}

	//同步qq登录
	public static function syncQQUser($token, $openId, $profile){
		$attr['nick'] = $profile['nickname'];
		$attr['avatar'] = $profile['figureurl_qq_2'];
		return self::syncUser($token, $openId, $attr, self::PLAT_QQ);
	}

	//同步微博登录
	public static function syncWBUser($tokenArr, $profile){
		$token = $tokenArr['access_token'];
		$openId = $tokenArr['uid'];
		$attr['nick'] = $profile['screen_name'];
		$attr['avatar'] = $profile['avatar_large'];
		return self::syncUser($token, $openId, $attr, self::PLAT_WB);
	}

	//同步微信登录
	public static function syncWechatUser($token, $openId, $unionId, $profile){
		$attr['nick'] = $profile['nickname'];
		$attr['avatar'] = $profile['headimgurl'];
		return self::syncUser($token, $openId, $attr, self::PLAT_WECHAT, array('union_id' => $unionId));
	}

	//同步微信公众帐号登录
	public static function syncWechatMpUser($token, $openId, $unionId, $profile){
		$attr['nick'] = $profile['nickname'];
		$attr['avatar'] = $profile['headimgurl'];
		//先看一下是否有相同的union_id, 有的话直接更新open_id即可
		$platform = self::model()->findByAttributes(array('union_id' => $unionId));
		if($platform){
			$platform->platform = self::PLAT_WECHAT_MP;
			$platform->open_id = $openId;
			$platform->token = $token;
			$res = $platform->update(array('open_id', 'access_token', 'platform'));
			return $res ? $platform : false;
		}
		return self::syncUser($token, $openId, $attr, self::PLAT_WECHAT_MP, array('union_id' => $unionId));
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserPlatformBind the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
