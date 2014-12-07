<?php

/**
 * This is the model class for table "killend_wl".
 *
 * The followings are the available columns in table 'killend_wl':
 * @property string $id
 * @property string $open_id
 * @property string $name
 * @property string $ctime
 */
class KillendWl extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'killend_wl';
	}

	public static function has($helperId){
		$record = self::model()->findByAttributes(array('open_id' => $helperId), array('select' => 'id'));
		return $record ? true : false;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return KillendWl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
