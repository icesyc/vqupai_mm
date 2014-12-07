<?php

/**
 * This is the model class for table "purchase".
 *
 * The followings are the available columns in table 'purchase':
 * @property string $id
 * @property string $shop_id
 * @property string $title
 * @property string $model
 * @property string $contract_id
 * @property string $price
 * @property string $mprice
 * @property string $num
 * @property string $in_num
 * @property string $arrive_date
 * @property string $onsale_date
 * @property string $settle_date
 * @property string $pay_date
 * @property string $remark
 * @property integer $status
 * @property string $ctime
 * @property string $operator
 */
class Purchase extends CActiveRecord
{

	public $shop_title;
	public $contract_title;
	public $search_type; // 给库存管理-入库管理使用
	public $status_text; //状态文字信息
	public $third_ship_text; //第三方发货文字信息
	public $process_storage; //判断是否需要处理剩余库存

	const STATUS_ON = 0;
	const STATUS_STORAGE_WAITE_PROCESS = 1;
	const STATUS_REFUNDED = 2;
	const STATUS_TRANSED = 3;
	const STATUS_FINISH = 4;
	const STATUS_TOREFUNDED = 5;

	public static $status_list = array(
		self::STATUS_ON => '进行中',
		self::STATUS_STORAGE_WAITE_PROCESS => '库存待处理',
		self::STATUS_REFUNDED => '已退货',
		self::STATUS_TRANSED => '已转单',
		self::STATUS_FINISH => '已完成',
		self::STATUS_TOREFUNDED => '待退货',
	);	

	const THIRD_SHIP_N = 0;
	const THIRD_SHIP_Y = 1;

	public static $is_third = array(
		self::THIRD_SHIP_N => '否', 
		self::THIRD_SHIP_Y => '是',
	);	

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, model, contract_id, num, arrive_date, onsale_date, offsale_date, settle_date, pay_date, ctime, operator, third_ship, oprice', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('shop_id, price, mprice, oprice, num, in_num, storage_num', 'length', 'max'=>10),
			array('shop_id, price, mprice, oprice, num, in_num, storage_num', 'numerical'),
			array('title, contract_id, onsale_date, operator', 'length', 'max'=>32),
			array('model', 'length', 'max'=>128),
			array('remark, third_ship', 'safe'),
			array('onsale_date', 'compare', 'compareAttribute' => 'arrive_date', 'operator' => '>=', 'message' => '上架日不能早于到货日'),
			array('offsale_date', 'compare', 'compareAttribute' => 'onsale_date', 'operator' => '>=', 'message' => '下架日不能早于上架日'),
			array('settle_date', 'compare', 'compareAttribute' => 'offsale_date', 'operator' => '>=', 'message' => '结算日不能早于下架日'),
			array('pay_date', 'compare', 'compareAttribute' => 'settle_date', 'operator' => '>=', 'message' => '付款日不能早于结算日'),
			array('arrive_date', 'checkArrive'),
			array('settle_date', 'check15days'),
			array('pay_date', 'check7days'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, shop_id, title, model, contract_id, price, oprice, mprice, num, in_num, storage_num, arrive_date, onsale_date, offsale_date, settle_date, pay_date, remark, status, ctime, operator, shop_title', 'safe', 'on'=>'search'),
		);
	}

	public function checkArrive(){
		$today = strtotime(date('Ymd'));
		$arrive = strtotime($this->arrive_date);
		if($arrive < $today){
			$this->addError('arrive_date', '到货日期不能早于今天');
		}
	}

	public function check15days(){
		$od = intval(strtotime($this->offsale_date) + 15*24*3600);
		$sd = intval(strtotime($this->settle_date));
		if($sd <= $od){
			$this->addError('settle_date', '结算日必须在下架日15天之后');
		}
	}

	public function check7days(){
		$od = intval(strtotime($this->settle_date) + 7*24*3600);
		$sd = intval(strtotime($this->pay_date));
		if($sd <= $od){
			$this->addError('pay_date', '付款日必须在结算日7天之后');
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'contract' => array(self::BELONGS_TO, 'Contract', 'contract_id', 'select' => 'title, shop_id', 'joinType' => 'inner join'),
			'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id', 'select' => 'title', 'joinType' => 'inner join'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'shop_id' => '商家id',
			'title' => '商品名称',
			'model' => '型号',
			'contract_id' => '合同编号',
			'price' => '进货价格',
			'mprice' => '市场价格',
			'oprice' => '原价',
			'num' => '应到数量',
			'in_num' => '入库数量',
			'storage_num' => '库存数量',
			'arrive_date' => '到货日期',
			'onsale_date' => '上架日期',
			'offsale_date' => '下架日期',
			'settle_date' => '结算日',
			'pay_date' => '付款日',
			'third_ship' => '第三方发货 0-否 1-是',
			'remark' => '备注',
			'status' => '库存状态',
			'ctime' => '创建时间',
			'operator' => '操作人',
			'shop_title' => '商家名称',
			'contract_title' => '合同名称'
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

		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id,false);
		$criteria->compare('shop_id',$this->shop_id,false);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('contract_id',$this->contract_id,false);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('mprice',$this->mprice,true);
		$criteria->compare('oprice',$this->oprice,true);
		$criteria->compare('num',$this->num,true);
		$criteria->compare('in_num',$this->in_num,true);
		$criteria->compare('storage_num',$this->in_num,true);
		$criteria->compare('arrive_date',$this->arrive_date,false);
		$criteria->compare('onsale_date',$this->onsale_date,false);
		$criteria->compare('offsale_date',$this->onsale_date,false);
		$criteria->compare('settle_date',$this->settle_date,false);
		$criteria->compare('pay_date',$this->pay_date,false);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('ctime',$this->ctime,true);
		$criteria->compare('operator',$this->operator,true);
		$criteria->compare('shop.title', $this->shop_title, true);
		$criteria->compare('contract.title', $this->contract_title, true);
		// 给库存管理-入库管理使用
		if($this->search_type == 'storage'){
			$criteria->addCondition('num != in_num and third_ship = 0 and status = '.self::STATUS_ON);
			$criteria->order='arrive_date';
		}
		//
		$criteria->with = array('shop', 'contract');

		if($ctime && $equal){
			$criteria->addBetweenCondition('ctime', $ctime, $ctime + 86400);
		}else{
			$criteria->compare('ctime',$ctime,true);
		}

		$sort = new CSort();
		$sort->attributes = array(
			'*',
			'shop_title' => array('asc' => 'shop.title', 'desc' => 'shop.title desc'),
			'contract_title' => array('asc' => 'contract.title', 'desc' => 'contract.title desc'),
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
	 * @return Purchase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getStatusList(){
		return array_merge(array('' => '全部'), self::$status_list);
	}

	protected function afterFind(){
		$this->status != null && $this->status_text = self::$status_list[$this->status];
		$this->third_ship != null && $this->third_ship_text = self::$is_third[$this->third_ship];
		//$today = time();
		//if((strtotime($this->offsale_date . ' 23:59:59') < $today) and ($this->storage_num > 0) and ($this->status == self::STATUS_ON))
		//	$this->process_storage = 1;
		//else
		//	$this->process_storage = 0;
	}
}
