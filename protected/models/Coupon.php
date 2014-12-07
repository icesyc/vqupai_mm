<?php

/**
 * This is the model class for table "coupon".
 *
 * The followings are the available columns in table 'coupon':
 * @property string $id
 * @property string $name
 * @property string $pic_url
 * @property string $description
 * @property string $value
 * @property integer $is_bind
 * @property integer $disabled
 * @property string $expire_time
 */
class Coupon extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'coupon';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, pic_url, description', 'required'),
			array('is_bind, disabled', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>32),
			array('pic_url', 'length', 'max'=>128),
			array('description', 'length', 'max'=>256),
			array('value, expire_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, pic_url, description, value, is_bind, disabled, expire_time', 'safe', 'on'=>'search'),
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '自增id',
			'name' => '拍券名称',
			'pic_url' => '拍券的图片url',
			'description' => '拍券的描述',
			'value' => '拍券的金额',
			'is_bind' => '是否绑定',
			'disabled' => '是否被禁用 0 未禁用 1 已禁用',
			'expire_time' => '过期时间',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pic_url',$this->pic_url,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('is_bind',$this->is_bind);
		$criteria->compare('disabled',$this->disabled);
		$criteria->compare('expire_time',$this->expire_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getExpireTime($from=null){
		!$from && $from = time();
		return $this->expire_time > 0 ? $from + $this->expire_time * 86400 : 0;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Coupon the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
