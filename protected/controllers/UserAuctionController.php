<?php
/**
 * 用户拍卖 微信帮拍
 */

Yii::import('application.extensions.wechat.Wechat');
class UserAuctionController extends Controller
{
	public $layout = false;

	public function init(){
		//return;
		Yii::app()->session->open();
		if(!isset(Yii::app()->session['open_id'])){
			$this->requestOpenId();
		}
	}

	public function requestOpenId(){
		$config['redirectUri'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$wechat = new Wechat($config);
		if(!isset($_GET['code'])){
			$wechat->authorize();
			Yii::app()->end();
		}
		$openInfo = $wechat->getOpenId($_GET['code']);
		if(!$openInfo){
			$this->error(111, 'invalid request');
		}
		Yii::app()->session['open_id'] = $openInfo['openid'];
	}

	public function actionShow(){
		$this->actionIndex();
	}

	public function actionIndex(){
		//获取帮杀是来自微信好友、群、朋友圈，这里和app内帮杀不一样了，不可拷贝到app帮杀里
		$from = 'nothing';
		if(isset($_SERVER['REQUEST_URI'])){
			if(strpos($_SERVER['REQUEST_URI'], 'from=groupmessage')){
				$from = 'groupmessage';
			}
			elseif (strrpos($_SERVER['REQUEST_URI'], 'from=singlemessage')) {
				$from = 'singlemessage';
			}
			elseif (strrpos($_SERVER['REQUEST_URI'], 'from=timeline')) {
				$from = 'timeline';
			}
		}
		Yii::app()->session['help_from'] = $from;

		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if($id <= 0){
			$this->error(111, 'param error');

		}
		$criteria = new CDbCriteria;
		$criteria->with = array(
			'item' => array('select' => 'id,title,pic_cover,oprice'),
			'user'
		);
		$auction = UserAuction::model()->findByPk($id, $criteria);
		if(!$auction){
			$this->error(111, 'auction not exists');
		}

		$data['auction'] = $auction->attributes;
		$pic_array = explode("/", $auction->item->pic_cover);
		$auction->item->pic_cover = '/'.$pic_array[1].'/'.$pic_array[2].'/'.$pic_array[3].'/200.'.$pic_array[4];
		$data['item'] = $auction->item->attributes;
		$data['user'] = $auction->user->attributes;

		$data['loger']['auction_id'] = $id;
		$data['loger']['item_id'] = $auction->item->id;
		$data['loger']['uid'] = $auction->user->id;
		$data['loger']['open_id'] = Yii::app()->session['open_id'];

		//获取帮拍记录
		$data['helper_count'] = $auction->help_num;
		$wx_avatar = 'images-share/wx_avatar.png';
		$help_record = array();
		$cri = new CDbCriteria;
		$cri->condition = sprintf('auction_id = %d', $id);
		$cri->select = 'id,uid,open_id,source,discount,ctime';
		$cri->order = 't.id desc';
		$cri->limit = 30;
		$cri->with = array('user');
		$helpers = UserAuctionHelper::model()->findAll($cri);
		$helpers = UserAuctionHelper::model()->mapUser($helpers);
		$help_record = array();
		foreach($helpers as $helper){
			$help_record[] = array(
				'uid' => $helper->user->id,
				'nick' => $helper->user->nick,
				'avatar' => $helper->user->avatar,
				'discount' => $helper->discount,
				'ctime' => $this->timeAgo($helper->ctime)
			);
		}
		$data['helpers'] = $help_record;

		if(!$data['user']['avatar']){
			$data['user']['avatar'] = '/images/100.jpg';
		}

		if($data['auction']['status'] > 1) {

			$criteria = new CDbCriteria;
			$criteria->condition = sprintf('channel = %d', UserAuctionPool::CHANNEL_LOWEST);
			$criteria->limit = 3;
			$criteria->with = array(
				'item' => array('select' => 'id,title,pic_cover'),
			);
			$auctions = UserAuctionPool::model()->findAll($criteria);
			foreach($auctions as $auction){
				$auction->item->pic_cover = $this->getImageBySize($auction->item->pic_cover, 200);
				$data['items'][] = $auction->item->attributes;
			}
			$data['loger']['page'] = 'share_success';
			$this->render('/wxkill/share_success', $data);
		}
		else {
			$data['loger']['page'] = 'kill';
			$this->render('/wxkill/kill', $data);
		}
	}

	public function actionSuccess(){
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$discount = isset($_GET['discount']) ? floatval($_GET['discount']) : 0;
		if($id <= 0){
			$this->error(111, 'param error');
		}
		$criteria = new CDbCriteria;
		$criteria->with = array(
			'item' => array('select' => 'id,title,pic_cover,oprice'),
			'user'
		);
		$auction = UserAuction::model()->findByPk($id, $criteria);
		if(!$auction){
			$this->error(111, 'auction not exists');
		}

		$data['loger']['auction_id'] = $id;
		$data['loger']['item_id'] = $auction->item->id;
		$data['loger']['uid'] = $auction->user->id;
		$data['loger']['open_id'] = Yii::app()->session['open_id'];

		$data['user_auction'] = $auction->attributes;
		$data['item'] = $auction->item->attributes;
		$data['discount'] = '您帮忙杀掉了<span class="colorff2 font_25">'.$discount.'</span>元';
		$data['user'] = $auction->user->attributes;

		if(isset($_GET['sec'])) {
			$exists = UserAuctionHelper::model()->find(sprintf("auction_id=%d and open_id='%s'", $id, Yii::app()->session['open_id']));
			if($exists) {
				$data['discount'] = '您已经帮杀过<span class="colorff2 font_25">'.$exists->discount.'</span>元了哦～';
			}
		}

		//获取帮拍记录
		$data['helper_count'] = $auction->help_num;
		$wx_avatar = 'images-share/wx_avatar.png';
		$help_record = array();
		$cri = new CDbCriteria;
		$cri->condition = sprintf('auction_id = %d', $id);
		$cri->order = 't.id desc';
		$cri->limit = 30;
		$cri->with = array('user');
		$helpers = UserAuctionHelper::model()->findAll($cri);
		$helpers = UserAuctionHelper::model()->mapUser($helpers);
		$help_record = array();
		foreach($helpers as $helper){
			$help_record[] = array(
				'uid' => $helper->user->id,
				'nick' => $helper->user->nick,
				'avatar' => $helper->user->avatar,
				'discount' => $helper->discount,
				'ctime' => $this->timeAgo($helper->ctime)
			);
		}
		$data['helpers'] = $help_record;

		$criteria = new CDbCriteria;
		$criteria->condition = sprintf('channel = %d', UserAuctionPool::CHANNEL_LOWEST);
		$criteria->limit = 3;
		$criteria->with = array(
			'item' => array('select' => 'id,title,pic_cover'),
		);
		$auctions = UserAuctionPool::model()->findAll($criteria);
		foreach($auctions as $auction){
			$auction->item->pic_cover = $this->getImageBySize($auction->item->pic_cover, 200);
			$data['items'][] = $auction->item->attributes;
		}
		if(!$data['user']['avatar']){
			$data['user']['avatar'] = '/images/100.jpg';
		}
		$data['loger']['page'] = 'kill_success';
		$this->render('/wxkill/kill_success', $data);
	}

	//微信的帮拍
	public function actionHelp(){
		$openId = Yii::app()->session['open_id'];

		//区分来自群、好友、朋友圈
			switch (Yii::app()->session['help_from']) {
				case 'singlemessage':
					$source = UserAuctionHelper::SRC_WECHAT_S;
					break;
				case 'timeline':
					$source = UserAuctionHelper::SRC_WECHAT_T;
					break;
				default:
					$source = UserAuctionHelper::SRC_WECHAT_G;
					break;
			}

		$auctionId = isset($_GET['id']) ? intval($_GET['id']) : 0;
		try{
			$auction = UserAuction::model()->findByPk($auctionId);
			UserAuctionHelper::canHelp($openId, $auction);
		}catch(Exception $e){
			$this->error($e->getCode(), $e->getMessage());
		}
		$res = $auction->takeHelp($openId, $source);
		$res ? $this->success($res) : $this->error(1108, '操作失败，请重试');
	}

	public function renderJSON($data){
		header('Content-type: application/json');
    	echo json_encode($data);
    	Yii::app()->end();
	}

	public function error($code, $msg=''){
		$data['code'] = $code;
		if(strlen($msg) > 0){
			$data['msg'] = $msg;
		}
		$this->renderJSON($data);
	}

	public function success($data=array()){
		$data['success'] = true;
		$this->renderJSON($data);
	}
}
