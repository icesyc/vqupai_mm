<?php

/**
 * This is the model class for table "user_auction_pool".
 *
 * The followings are the available columns in table 'user_auction_pool':
 * @property string $id
 * @property string $item_id
 * @property string $start_price
 * @property string $reserve_price
 * @property string $price_interval
 * @property string $left_num
 * @property integer $status
 * @property string $display_order
 * @property string $ctime
 */
class UserAuctionPool extends CActiveRecord
{

	const STATUS_NOT_START = 0;
	const STATUS_ONLINE = 1;
	const STATUS_FINISH = 2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_auction_pool';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'item' => array(self::BELONGS_TO, 'Item', 'item_id', 'joinType' => 'inner join')
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserAuctionPool the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
