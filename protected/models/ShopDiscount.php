<?php

/**
 * This is the model class for table "shop_discount".
 *
 * The followings are the available columns in table 'shop_discount':
 * @property string $id
 * @property string $title
 * @property string $pic_url
 * @property string $description
 * @property string $url
 * @property integer $only_once
 * @property string $expire_time
 */
class ShopDiscount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shop_discount';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShopDiscount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
