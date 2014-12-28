<?php

/**
 * This is the model class for table "circle".
 *
 * The followings are the available columns in table 'circle':
 * @property string $id
 * @property string $uid
 * @property string $content
 * @property string $ctime
 * @property string $json_data
 */
class Circle extends CActiveRecord
{
	//动态类型 普通 晒单 新拍, 创建血战到底
	const FEED_COMMON = 0;
	const FEED_SHOW_ORDER = 1;
	const FEED_NEW_BUY = 2;
	const FEED_NEW_AUCTION = 3;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'circle';
	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'uid', 'select' => 'nick,avatar', 'joinType' => 'inner join')
		);
	}

	public static function increase($id, $field){
		$rows = self::model()->updateCounters(array($field => 1), "id=$id");
		return true;
	}

	public static function decrease($id, $field){
		$rows = self::model()->updateCounters(array($field => -1), "id=$id and $field > 0");
		return true;
	}

	public static function postShowOrder($uid, $content, $data){
		$circle = new self;
		$circle->uid = $uid;
		$circle->content = $content;
		$circle->ctime = time();
		$circle->feed_type = Circle::FEED_SHOW_ORDER;
		$circle->json_data = json_encode($data);
		if($circle->insert()){
			UserStat::increase($uid, 'show_order_num');
			//发一条到消息队列推给所有用户
			$msg['feed_id'] = $circle->id;
			MQ::sendSys(MQ::MSG_CIRCLE, $msg);
			return $circle;
		}
		return false;
	}

	public static function postNewAuction($uid, $data){
		$circle = new self;
		$circle->uid = $uid;
		$circle->ctime = time();
		$circle->content = '创建了血战到底';
		$circle->feed_type = Circle::FEED_NEW_AUCTION;
		$circle->json_data = json_encode($data, true);
		if($circle->insert()){
			//发一条系统消息
			$msg['feed_id'] = $circle->id;
			MQ::sendSys(MQ::MSG_CIRCLE, $msg);
			return $circle;
		}
		return false;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Circle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function afterFind(){
		if($this->json_data){
			$this->json_data = json_decode($this->json_data, true);
		}
	}
}
