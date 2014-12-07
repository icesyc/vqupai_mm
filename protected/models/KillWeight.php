<?php

/**
 * This is the model class for table "kill_weight".
 *
 * The followings are the available columns in table 'kill_weight':
 * @property string $id
 * @property string $src
 * @property string $trg
 * @property string $suid
 * @property string $tuid
 * @property string $num
 * @property string $weight
 */
class KillWeight extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'kill_weight';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return KillWeight the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
