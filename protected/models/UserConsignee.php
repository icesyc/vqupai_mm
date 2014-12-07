<?php

/**
 * This is the model class for table "user_consignee".
 *
 * The followings are the available columns in table 'user_consignee':
 * @property string $id
 * @property string $uid
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $address
 * @property string $mobile
 * @property string $zip
 * @property string $email
 * @property integer $is_default
 */
class UserConsignee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_consignee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, province, address, mobile, zip, email', 'required'),
			array('is_default, mobile, zip', 'numerical', 'integerOnly'=>true),
			array('uid', 'length', 'max'=>10),
			array('name, province, city, mobile, zip, email', 'length', 'max'=>32),
			array('address', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, name, province, city, address, mobile, zip, email, is_default', 'safe', 'on'=>'search'),
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
			'uid' => '用户id',
			'name' => '姓名',
			'province' => '省份',
			'city' => '城市',
			'address' => '详细地址',
			'mobile' => '手机',
			'zip' => '邮编',
			'email' => '电子邮箱',
			'is_default' => '是否为默认地址',
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
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('province',$this->province,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('is_default',$this->is_default);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserConsignee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
