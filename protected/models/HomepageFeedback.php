<?php

/**
 * This is the model class for table "homepage_feedback".
 *
 * The followings are the available columns in table 'homepage_feedback':
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $content
 * @property string $ctime
 */
class HomepageFeedback extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'homepage_feedback';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, ctime', 'required'),
			array('name', 'length', 'max'=>32),
			array('email', 'length', 'max'=>64),
			array('phone', 'length', 'max'=>20),
			array('content', 'length', 'max'=>255),
			array('ctime', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, email, phone, content, ctime', 'safe', 'on'=>'search'),
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
			'id' => 'id',
			'name' => '名字',
			'email' => '邮箱',
			'phone' => '电话',
			'content' => '反馈内容',
			'ctime' => '时间',
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('ctime',$this->ctime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HomepageFeedback the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
