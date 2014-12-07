<?php

/**
 * This is the model class for table "item".
 *
 * The followings are the available columns in table 'item':
 * @property string $id
 * @property string $title
 * @property string $model
 * @property string $mprice
 * @property string $shop_id
 * @property string $pic_url
 * @property string $description
 * @property string $ctime
 */
class Item extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'item';
	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id', 'joinType' => 'inner join'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Item the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getPicList(){
    	return $this->pic_url ? explode("|", $this->pic_url) : array();
    }
}
