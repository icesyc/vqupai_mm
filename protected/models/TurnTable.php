<?php

/**
 * This is the model class for table "turn_table".
 *
 * The followings are the available columns in table 'turn_table':
 * @property integer $id
 * @property integer $award_id
 * @property string $award_name
 * @property integer $stock_num
 * @property integer $probability
 * @property integer $status
 * @property integer $num
 */
class TurnTable extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'turn_table';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, award_id, award_name, stock_num, probability, status, num', 'required'),
			array('id, award_id, stock_num, probability, status, num', 'numerical', 'integerOnly'=>true),
			array('award_name', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, award_id, award_name, stock_num, probability, status, num', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'award_id' => 'Award',
			'award_name' => 'Award Name',
			'stock_num' => 'Stock Num',
			'probability' => 'Probability',
			'status' => 'Status',
			'num' => 'Num',
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
		$criteria->compare('award_id',$this->award_id);
		$criteria->compare('award_name',$this->award_name,true);
		$criteria->compare('stock_num',$this->stock_num);
		$criteria->compare('probability',$this->probability);
		$criteria->compare('status',$this->status);
		$criteria->compare('num',$this->num);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TurnTable the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
