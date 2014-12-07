<?php

/**
 * This is the model class for table "user_auction_prop".
 *
 * The followings are the available columns in table 'user_auction_prop':
 * @property string $id
 * @property string $auction_id
 * @property string $uid
 * @property string $prop_id
 * @property string $duration
 * @property string $action
 * @property string $ctime
 * @property string $auction_type
 */
class UserAuctionProp extends CActiveRecord
{

	const TYPE_SYSTEM = 1; //系统拍卖
	const TYPE_USER = 2; //用户拍卖

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_auction_prop';
	}

	//返回道具的剩余时间
	public function getLeftTime(){
		return max(0, $this->ctime + $this->duration * 60 - time());
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserAuctionProp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
