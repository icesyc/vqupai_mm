<?php

/**
 * This is the model class for table "helper_stat".
 *
 * The followings are the available columns in table 'helper_stat':
 * @property string $id
 * @property string $helper_id
 * @property string $total_num
 * @property integer $day_num
 * @property string $utime
 */
class HelperStat extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'helper_stat';
	}

	public static function get($helperId){
		return self::model()->findByAttributes(array('helper_id' => $helperId));
	}

	//帮拍次数计数增加，记录不存在创建, day_num只只保存每天的
	public static function increase($helperId){
		$record = self::model()->findByAttributes(array('helper_id' => $helperId));
		if(!$record){
			$record = new self;
			$record->helper_id = $helperId;
			$record->total_num = 1;
			$record->day_num = 1;
			$record->utime = time();
			return $record->insert();
		}else{
			//如果上次更新时间不是今天，重新累加
			$midnight = strtotime('today midnight');
			if($record->utime < $midnight){
				$record->day_num = 1;
			}else{
				$record->day_num++;
			}
			$record->total_num++;
			$record->utime = time();
			return $record->update();
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HelperStat the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
