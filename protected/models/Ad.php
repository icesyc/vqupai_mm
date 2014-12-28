<?php

/**
 * This is the model class for table "ad".
 *
 * The followings are the available columns in table 'ad':
 * @property string $id
 * @property string $pic_url
 * @property string $link
 * @property integer $shop_id
 * @property integer $status
 */
class Ad extends CActiveRecord
{

	const TYPE_AD = 1;
	const TYPE_ACT = 2;
	const TYPE_BOMB = 3;

	//首页
	const CHANNEL_HOME = 1;
	//拍圈
	const CHANNEL_CIRCLE = 2;
	//wap广告
	const CHANNEL_WAP = 3;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ad';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pic_url, type', 'required'),
			array('shop_id, status', 'numerical', 'integerOnly'=>true),
			array('pic_url, link', 'length', 'max'=>128),
			array('title, auction_id, display_order, channel', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, auction_id, pic_url, link, shop_id, status, type, width, height', 'safe', 'on'=>'search'),
		);
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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ad the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
