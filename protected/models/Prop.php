<?php

/**
 * This is the model class for table "prop".
 *
 * The followings are the available columns in table 'prop':
 * @property string $id
 * @property integer $type
 * @property string $name
 * @property string $pic_url
 * @property string $description
 * @property integer $is_bind
 * @property integer $disabled
 * @property string $item_id
 * @property string $auction_id
 * @property string $expire_time
 * @property string $action
 */
class Prop extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'prop';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	public function getAllText()
	{
		$rows = self::model()->findAll();
		$ret = array();
		foreach($rows as $row){
			$ret[$row->id] = $row->name;
		}
		return $ret;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Prop the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
