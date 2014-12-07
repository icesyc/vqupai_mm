<?php

/**
 * This is the model class for table "killend_bl".
 *
 * The followings are the available columns in table 'killend_bl':
 * @property string $id
 * @property string $block_id
 * @property integer $reason
 * @property string $ctime
 */
class KillendBl extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'killend_bl';
	}

	public static function has($helperId){
		$record = self::model()->findByAttributes(array('block_id' => $helperId), array('select' => 'id'));
		return $record ? true : false;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return KillendBl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
