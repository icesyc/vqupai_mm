<?php
/**
 * 商品详情对应的控制器
 * 如果有系统拍卖，会返回系统拍卖的信息
 */

class ItemController extends Controller
{
	public $layout = false;

	public function actionShow(){
		$this->actionIndex();
	}

	//商品详情
	public function actionIndex(){
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if($id <= 0){
			return;
		}
		$criteria = new CDbCriteria;
		$criteria->select = 'id,title,oprice,pic_cover,pic_url,pic_top,description,shop_id,brand_id,model,specification,content';
		$item = Item::model()->findByPk($id, $criteria);
		if(!$item){
			echo 'item not exists';
			exit;
		}

		$item->pic_url = $item->getPicList();
		if(!$item->content){
			$item->content = $item->description;
		}
		if(!$item->pic_top){
			$item->pic_top = $item->pic_cover;
		}
		$data['item'] = $item->attributes;
		$data['auction'] = $this->getAuction($id);
		$this->render('/v2/item', $data);
	}

	//获取该商品系统的自动拍卖
	private function getAuction($itemId){
		$criteria = new CDbCriteria;
		$criteria->select = 'id,item_id,curr_price,status,round_start_time,start_time,time_interval,curr_round';
		$criteria->condition = sprintf('item_id=%d and status = %d', $itemId, Auction::STATUS_ONLINE);
		$auction = Auction::model()->find($criteria);
		if(!$auction){
			return null;
		}
		return $auction->attributes;
	}
}