<?php
/**
 * 优惠详情
 */

class DiscountController extends Controller
{
	public $layout = false;

	public function actionShow(){
		$this->actionIndex();
	}

	public function actionIndex(){
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if($id <= 0){
			echo 'param error';
			exit;
		}
		$discount = ShopDiscount::model()->findByPk($id);
		if(!$discount){
			echo 'item not exists';
			exit;
		}

		$data['discount'] = $discount->attributes;
		$this->render('/v2/discount', $data);
	}
}