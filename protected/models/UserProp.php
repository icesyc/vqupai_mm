<?php

/**
 * This is the model class for table "user_prop".
 *
 * The followings are the available columns in table 'user_prop':
 * @property string $id
 * @property string $uid
 * @property string $prop_id
 * @property string $num
 * @property string $prop_info
 */
class UserProp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_prop';
	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'prop' => array(self::BELONGS_TO, 'Prop', 'prop_id', 'joinType' => 'inner join')
		);
	}

	/**
	 * 用户对拍卖应用道具
	 *
	 * @param object $target 拍卖对象
	 * @return boolean
	 */
	public function applyProp($target){
		if($this->num == 0) return false;
		$trans = Yii::app()->db->beginTransaction();
		try{
			//是用户拍卖还是系统拍卖?
			$type = $target instanceof UserAuction ? UserAuctionProp::TYPE_USER : UserAuctionProp::TYPE_SYSTEM;

			//对拍卖应用道具
			if(!PropAction::apply($this->prop, $target)){
				return false;
			}

			//增加一条道具信息到拍卖道具列表中
			$uap = new UserAuctionProp;
			$uap->uid = $this->uid;
			$uap->auction_id = $target->id;
			$uap->prop_id = $this->prop_id;
			$uap->duration = $this->prop->duration;
			$uap->action = $this->prop->action;
			$uap->ctime = time();
			$uap->auction_type = $type;
			$uap->insert();
			
			//更新用户道具的数量
			$this->num--;
			$this->update(array('num'));

			$trans->commit();
			return true;
		}catch(CDbException $e){
			$trans->rollback();
			return false;
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserProp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
