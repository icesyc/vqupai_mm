<?php

/**
 * This is the model class for table "user_verification".
 *
 * The followings are the available columns in table 'user_verification':
 * @property string $identity
 * @property string $type
 * @property string $verifyString
 * @property string $token
 * @property string $expireTime
 * @property integer $sended
 */
class UserVerification extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_verification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('identity, type, verifyString, token, expireTime', 'required'),
			array('sended', 'numerical', 'integerOnly'=>true),
			array('identity, token', 'length', 'max'=>32),
			array('type', 'length', 'max'=>16),
			array('verifyString', 'length', 'max'=>64),
			array('expireTime', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('identity, type, verifyString, token, expireTime, sended', 'safe', 'on'=>'search'),
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
			'identity' => '用户标识',
			'type' => '标识类型',
			'verifyString' => '验证串',
			'token' => '识别串',
			'expireTime' => '过期时间',
			'sended' => '是否有效 0:未发送; 1:已发送;',
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

		$criteria->compare('identity',$this->identity,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('verifyString',$this->verifyString,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('expireTime',$this->expireTime,true);
		$criteria->compare('sended',$this->sended);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserVerification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
