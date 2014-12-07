<?php

/**
 * This is the model class for table "auction".
 *
 * The followings are the available columns in table 'auction':
 * @property string $id
 * @property string $item_id
 * @property string $start_price
 * @property string $reserve_price
 * @property string $curr_price
 * @property string $total_num
 * @property string $left_num
 * @property string $start_time
 * @property string $end_time
 * @property string $time_interval
 * @property string $price_interval
 * @property integer $change_type
 * @property string $start_date
 * @property integer $status
 * @property string $last_change_time
 * @property string $reserve_change_time
 */
class Auction extends CActiveRecord
{
	const STATUS_NOT_START = 0;
	const STATUS_SETTLE = 1;
	const STATUS_ONLINE = 2;
	const STATUS_FINISH = 3;

	const PRICE_FIXED = 1;
	const PRICE_PERCENT = 2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'auction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, start_price, reserve_price, curr_price, total_num, left_num, start_time, time_interval, price_interval, change_type, status, total_round', 'required'),
			array('change_type, status, total_num, left_num, total_round', 'numerical', 'integerOnly'=>true),
			array('start_price, reserve_price, curr_price', 'numerical'),
			//array('last_price', 'numerical', 'allowEmpty' => true),
			array('time_interval, price_interval', 'length', 'max'=>32),
			array('reserve_price', 'compare', 'compareAttribute' => 'start_price', 'operator' => '<=', 'message' => '保底价必须小于起拍价'),
			array('total_num', 'checkItemTotalNum'),
			//array('next_price, next_max_num', 'checkManualNext'),
			//array('next_price, next_max_num', 'numerical'),
			//array('next_max_num', 'compare', 'compareAttribute' => 'left_num', 'operator' => '<', 'message' => '下轮数量不能大于现有存量'),
			array('id, item_id, start_price, reserve_price, curr_price, total_num, left_num, start_time, end_time, time_interval, price_interval, change_type, status, curr_round, total_round, item_title, total_round', 'safe', 'on'=>'search'),
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
			'item' => array(self::BELONGS_TO, 'Item', 'item_id', 'joinType' => 'inner join'),
			'purchase' => array(self::BELONGS_TO, 'Purchase', 'item_id', 'select' => 'oprice', 'joinType' => 'inner join'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '拍卖编号',
			'item_id' => '商品编号',
			'start_price' => '起拍价',
			'reserve_price' => '保底价',
			'curr_price' => '当前价',
			'total_num' => '拍卖总量',
			'left_num' => '剩余量',
			'start_time' => '起拍时间',
			'end_time' => '结束时间',
			'time_interval' => '变价周期',
			'price_interval' => '变价范围',
			'change_type' => '变化类型',
			'total_round' => '拍卖轮数',
			'curr_round' => '当前轮次',
			'status' => '状态',
			'next_price' => '下次变价价格',
			'next_max_num' => '下次可拍数量',
			'item_title' => '拍品',
			'purchase_mprice' => '市场价',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$start_time = $this->start_time;
		$equal = true;
		if(preg_match('#^(<>|<=|>=|<|>|=)?(.+)$#', $this->start_time, $m)){
			if($m[1] != '' && $m[1] != '='){
				$start_time = $m[1] . strtotime($m[2]);
				$equal = false;
			}else{
				$start_time = strtotime($m[2]);
			}
		}
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id, false);
		$criteria->compare('item_id',$this->item_id,false);
		$criteria->compare('start_price',$this->start_price,true);
		$criteria->compare('reserve_price',$this->reserve_price,true);
		$criteria->compare('curr_price',$this->curr_price,true);
		$criteria->compare('total_num',$this->total_num,true);
		$criteria->compare('left_num',$this->left_num,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('time_interval',$this->time_interval,true);
		$criteria->compare('price_interval',$this->price_interval,true);
		$criteria->compare('change_type',$this->change_type);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('item.title', $this->item_title, true);
		if($start_time && $equal){
			$criteria->addBetweenCondition('start_time', $start_time, $start_time + 86400);
		}else{
			$criteria->compare('start_time',$start_time,true);
		}
		$criteria->with = array('item', 'purchase'); 

		$sort = new CSort();
		$sort->attributes = array(
			'*',
			'item_title' => array('asc' => 'item.title', 'desc' => 'item.title desc')
		);
		$sort->defaultOrder = 't.id desc';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => $sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Auction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
