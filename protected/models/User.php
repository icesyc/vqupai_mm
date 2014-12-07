<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $uname
 * @property string $passwd
 * @property string $salt
 * @property string $score
 * @property integer $level
 * @property string $friend_num
 * @property string $nick
 * @property string $avatar
 * @property string $real_name
 * @property integer $gender
 * @property string $email
 * @property string $reg_time
 * @property string $phone
 */
class User extends CActiveRecord
{
	public static $gender_list = array(
		1 => '男',
		2 => '女'
	);

	public $gender_text;
	public $is_svip_text;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	public static function login($uname, $passwd){
		$user = self::model()->findByAttributes(array('uname' => $uname));
		if(!$user || $user->passwd != md5($passwd . $user->salt)){
			throw new Exception('用户名或密码错误');
		}
		return $user;
	}

	//注册成功后的回调
	public function onRegisterSuccess(){
		//送拍券
		$this->sendCoupon();
		//送道具
		$this->sendProp();
		//加积分
		UserScore::add($this->id, UserScore::ACT_REGISTER);
	}

	//发放拍券
	public function sendCoupon(){
		//10元的
		$couponId = 1004;
		$coupon = Coupon::model()->findByPk($couponId);
		$uc = new UserCoupon;
		$uc->uid = $this->id;
		$uc->coupon_id = $coupon->id;
		$uc->expire_time = $coupon->getExpireTime();
		$uc->num = 1;
		if(!$uc->insert()){
			$msg = sprintf('insert user coupon error, uid=%d, coupon_id=%d', $this->id, $couponId);
			Yii::log($msg, 'error', 'database');
			return false;
		}
		MQ::send($this->id, MQ::MSG_NEW_COUPON, 1);
		return true;
	}

	//发放道具
	public function sendProp(){
		$props = Prop::model()->findAll();
		foreach($props as $prop){
			$up = new UserProp;
			$up->uid = $this->id;
			$up->prop_id = $prop->id;
			$up->num = 1;
			if(!$up->insert()){
				return false;
			}
		}
		MQ::send($this->id, MQ::MSG_NEW_PROP, 1);
		return true;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
