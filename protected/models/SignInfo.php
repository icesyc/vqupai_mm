<?php

/**
 * This is the model class for table "sign_info".
 *
 * The followings are the available columns in table 'sign_info':
 * @property integer $id
 * @property string $month
 * @property integer $day
 * @property integer $exp
 * @property integer $score
 * @property integer $coupon
 * @property integer $prop
 */
class SignInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sign_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('day, exp, score, coupon, prop', 'numerical', 'integerOnly'=>true),
			array('month', 'length', 'max'=>6),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, month, day, exp, score, coupon, prop', 'safe', 'on'=>'search'),
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
			'month' => '月份',
			'day' => '日',
			'exp' => '经验值',
			'score' => '积分',
			'coupon' => '拍券',
			'prop' => '道具',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('month',$this->month,true);
		$criteria->compare('day',$this->day);
		$criteria->compare('exp',$this->exp);
		$criteria->compare('score',$this->score);
		$criteria->compare('coupon',$this->coupon);
		$criteria->compare('prop',$this->prop);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getSignInfoMonth($month)
	{
		$signs = self::model()->findAllByAttributes(array('month' => $month));
		if($signs){
			$info = array();
			foreach($signs as $r){
				$info[] = array(
					'exp'=>$r->exp,
					'score'=>$r->score,
					'coupon'=>$r->coupon,
					'prop'=>$r->prop,
					);
			}
			return $info;
		}
		else{
			return false;
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SignInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
