<?php

/**
 * app的配置表
 * key list
 * wechat_price
 * 
 */
class Config extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'config';
	}

	public static function get($key){
		$model = self::model()->findByAttributes(array('key' => $key));	
		return $model ? $model->value : false;
	}

	public static function getKV($category){
		$configs = self::model()->findAllByAttributes(array('category' => $category));
		if(count($configs) > 0) {
			foreach($configs as $config) {
				$ret[$config->key] = $config->value;
			}
			return $ret;
		}
		else {
			return false;
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Config the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
