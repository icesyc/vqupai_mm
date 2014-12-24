<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property string $id
 * @property string $auction_id
 * @property string $item_id
 * @property string $uid
 * @property string $num
 * @property string $price
 * @property string $total_pay
 * @property string $consignee_info
 * @property string $payment
 * @property string $shipment
 * @property string $delivery_time
 * @property string $ctime
 * @property integer $status
 * @property string $comment
 */
class Order extends CActiveRecord
{
	public $item_title;
	public $item_model;
	public $username;
	public $nick;
	public $status_text;
	public $search_type;
	public $append_condition;
	public $shipment_ctime;
	public $shipment_id;
	public $shipment_name;
	public $order_num;
	public $shop_title;
	public $invite;
	public $info;
	public $auction_source;

	const STATUS_TOPAY = 0;
	const STATUS_TODEAL = 1;
	const STATUS_SHIPMENT = 2;
	const STATUS_FINISH = 3;
	const STATUS_CANCEL_NOPAY = 4;
	const STATUS_CANCEL_PAID = 5;
	const STATUS_UNUSUAL = 6;

	const TYPE_NORMAL = 1; //普通类型
	const TYPE_KILLEND = 2; //一拍到底
	const TYPE_ACT = 3; //活动产生的订单
	const TYPE_DISCOUNT = 4; //惠吃是喝的血战

	public static $status_list = array(
		self::STATUS_TOPAY => '未付款', 
		self::STATUS_TODEAL => '处理中',
		self::STATUS_SHIPMENT => '已发货',
		self::STATUS_FINISH => '已完成',
		self::STATUS_CANCEL_NOPAY => '取消未付款',
		self::STATUS_CANCEL_PAID => '取消已付款',
		self::STATUS_UNUSUAL => '异常订单',
	);	

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('auction_id, item_id, uid, num, status', 'numerical', 'integerOnly'=>true, 'allowEmpty' => false),
			array('price, total_pay', 'numerical', 'allowEmpty' => false),
			array('payment, delivery_time', 'length', 'max'=>32),
			array('comment', 'length', 'max'=>255),
			array('consignee_info,email,name,province,city,address,mobile,zip', 'safe'),
			array('auction_type','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, invite, auction_type, shop_id, shop_title, auction_id, nick, item_id, item_title, item_model, third_ship, uid, username, num, price, total_pay, consignee_info, payment, delivery_time, ctime, status, comment, shipment_id, shipment_ctime, name', 'safe', 'on'=>'search'),
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
			'prop' => array(self::HAS_ONE, 'OrderProp', 'order_id'),
			'coupon' => array(self::HAS_ONE, 'OrderCoupon', 'order_id'),
			'item' => array(self::BELONGS_TO, 'Item', 'item_id', 'select' => '*', 'joinType' => 'inner join'),
			'user' => array(self::BELONGS_TO, 'User', 'uid', 'select' => 'uname,nick,invite', 'joinType' => 'inner join'),
			'shipment' => array(self::HAS_ONE, 'Shipment', 'order_id'),
			'auction' => array(self::BELONGS_TO, 'Auction', 'auction_id'),
			'purchase' => array(self::BELONGS_TO, 'Purchase', 'item_id', 'select' => '*', 'joinType' => 'inner join'),
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id', 'select' => 'id,title', 'joinType' => 'inner join'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '订单ID',
			'auction_id' => '拍卖ID',
			'shop_id' => '商家ID',
			'item_id' => '商品ID',
			'uid' => 'UID',
			'num' => '数量',
			'price' => '商品价格',
			'total_pay' => '总付款',
			'payment' => '付款方式',
			'delivery_time' => '配送时间',
			'pay_time' => '付款时间',
			'ctime' => '下单时间',
			'status' => '状态',
			'cancel_reason' => '订单取消原因',
			'comment' => '备注',
			'item_title' => '拍品',
			'item_model' => '型号',
			'username' => '会员名',
			'consignee_info' => '收货人信息',
			'nick'=>'昵称',
			'shipment_ctime'=>'发货时间',
			'shipment_id'=>'快递单号',
			'shipment_name'=>'快递公司',
			'third_ship'=>'第三方',
			'trade_no' => '快递单号',
			'auction_type' => '拍卖类型',
			'coupon_info' => '拍券使用情况(json)',
			'shop_title'=>'商家名称',
			'invite'=>'邀请人',
			'purchase_price'=>'供货价',
			'name'=>'收货人',
			'province'=>'省份',
			'city'=>'城市',
			'address'=>'地址',
			'mobile'=>'手机',
			'zip'=>'邮编',
			'email'=>'email',
			'auction_source'=>'拍卖来源 0 app 1 微信',
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
		$ctime = $this->ctime;
		$equal = true;
		if(preg_match('#^(<>|<=|>=|<|>|=)?(.+)$#', $this->ctime, $m)){
			if($m[1] != '' && $m[1] != '='){
				$ctime = $m[1] . strtotime($m[2]);
				$equal = false;
			}else{
				$ctime = strtotime($m[2]);
			}
		}
		$shipment_ctime = $this->shipment_ctime;
		$shipment_equal = true;
		if(preg_match('#^(<>|<=|>=|<|>|=)?(.+)$#', $this->shipment_ctime, $m)){
			if($m[1] != '' && $m[1] != '='){
				$shipment_ctime = $m[1] . strtotime($m[2]);
				$shipment_equal = false;
			}else{
				$shipment_ctime = strtotime($m[2]);
			}
		}

		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id,false);
		$criteria->compare('t.shop_id',$this->shop_id,false);
		$criteria->compare('t.auction_type',$this->auction_type,false);
		$criteria->compare('t.auction_id',$this->auction_id,false);
		$criteria->compare('t.item_id',$this->item_id,false);
		$criteria->compare('uid',$this->uid,false);
		$criteria->compare('t.num',$this->num,true);
		$criteria->compare('t.price',$this->price,true);
		$criteria->compare('t.total_pay',$this->total_pay,true);
		//$criteria->compare('consignee_info',$this->consignee_info,true);
		$criteria->compare('t.payment',$this->payment,true);
		$criteria->compare('t.delivery_time',$this->delivery_time,true);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.comment',$this->comment,true);
		$criteria->compare('item.title', $this->item_title, true);
		$criteria->compare('item.model', $this->item_model, true);
		$criteria->compare('user.uname', $this->username, true);
		$criteria->compare('user.invite', $this->invite, true);
		$criteria->compare('user.nick', $this->nick, true);
		$criteria->compare('shipment.shipment_id', $this->shipment_id, false);
		$criteria->compare('shipment.name', $this->shipment_name, true);
		$criteria->compare('t.third_ship',$this->third_ship,true);
		$criteria->compare('shop.title',$this->shop_title,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.province',$this->province,true);
		$criteria->compare('t.city',$this->city,true);
		$criteria->compare('t.address',$this->address,true);
		$criteria->compare('t.mobile',$this->mobile,true);
		$criteria->compare('t.zip',$this->zip,true);
		$criteria->compare('t.email',$this->email,true);

		if($ctime && $equal){
			$criteria->addBetweenCondition('t.ctime', $ctime, $ctime + 86400);
		}else{
			$criteria->compare('t.ctime',$ctime,true);
		}

		if($shipment_ctime && $shipment_equal){
			$criteria->addBetweenCondition('shipment.ctime', $shipment_ctime, $shipment_ctime + 86400);
		}else{
			$criteria->compare('shipment.ctime',$shipment_ctime,true);
		}

		if($this->append_condition != '') $criteria->addCondition($this->append_condition);
/*
		//给发货订单列表用，只查找自己发货的订单
		if($this->search_type == 'storage'){
			$criteria->addCondition('t.third_ship = 0');
		}
		//给合作伙伴发货列表用
		if($this->search_type == 'supplier'){
			$criteria->addCondition('t.third_ship = 1');
			$criteria->addCondition($this->append_condition);
		}
*/
		//给拍卖实况用
		//if($tCondition != ''){
		//	$criteria->addCondition($tCondition);
		//}

		$criteria->with = array('item', 'user', 'shipment', 'shop');
		$sort = new CSort();
		$sort->attributes = array(
			'*',
			'item_title' => array('asc' => 'item.title asc', 'desc' => 'item.title desc'),
			'item_model' => array('asc' => 'item.model asc', 'desc' => 'item.model desc'),
			'shop_title' => array('asc' => 'shop.title asc', 'desc' => 'shop.title desc'),
			'username' => array('asc' => 'user.uname asc', 'desc' => 'user.uname desc'),
			'nick' => array('asc' => 'user.nick asc', 'desc' => 'user.nick desc'),
			'shipment_ctime' => array('asc' => 'shipment.ctime asc', 'desc' => 'shipment.ctime desc'),
			'shipment_id' => array('asc' => 'shipment.shipment_id asc', 'desc' => 'shipment.shipment_id desc'),
			'name' => array('asc' => 'shipment.name asc', 'desc' => 'shipment.name desc'),
		);
		$sort->defaultOrder = 't.id desc';
		
		return new CActiveDataProvider($this, array(
			'pagination' => array(
             	'pageSize' => 20,
        	),
			'criteria'=>$criteria,
			'sort' => $sort
		));
	}

	public function countGPR($auction_id){
		$orders = $this->model()->findAllByAttributes(array('auction_id' => $auction_id));
		$total = 0;
		$order_num = count($orders);
		if($order_num > 0){
			foreach($orders as $order){
				$total += floatval($order['total_pay']);
			}
			$auction = Auction::model()->with('purchase')->findByPk($auction_id);
			$total_cost = floatval($auction['purchase']['price']) * $order_num;
			$gpr = intval(($total - $total_cost)/$total_cost*10000)/100;
			$gpr .= '%';
		}
		else{
			$gpr = '没有订单';
		}
		return $gpr;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getStatusList(){
		return array_merge(array('' => '全部'), self::$status_list);
	}

	public function afterFind(){
		$this->shop_title = $this->item->shop->title;
		$this->status != null && $this->status_text = self::$status_list[$this->status];
		if($this->consignee_info){
			$this->info = $this->consignee_info;
		}
		if($this->consignee_info){
			$obj = new UserConsignee;
			$obj->attributes = json_decode($this->consignee_info, true);	
			$this->consignee_info = $obj;
		}
		if ($this->coupon_info) {
			$this->coupon_info=json_decode($this->coupon_info);
		}
		if($this->auction_type == self::TYPE_KILLEND){
			$s = UserAuction::model()->findByPk($this->auction_id);
			$this->auction_source = $s->source;
		}
		else{
			$this->auction_source = 0;
		}
	}

	public function afterDelete(){
		$this->coupon->delete();
		$this->prop->delete();	
	}
}
