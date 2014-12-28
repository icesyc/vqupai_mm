<?php 
/*
 * 广告组件
 */
class AdWidget extends CWidget{

	public function init(){

	}

	public function run(){
		$criteria = new CDbCriteria;
		$criteria->condition = 'status=1 and channel=' . Ad::CHANNEL_WAP;
		$data['ad'] = Ad::model()->find($criteria);
		$this->render('ad', $data);
	}
}