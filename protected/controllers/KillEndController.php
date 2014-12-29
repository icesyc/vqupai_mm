<?php
/*
 * 血战的创建和我的血战接口
 *
 * 错误代码11xx
 */

class KillEndController extends Controller
{
	public $assetUrl;

	public function init(){
	}

	//血战列表
	public function actionIndex(){
		$user = $this->wechatLogin();
		$criteria = new CDbCriteria;
		$criteria->select = 'id, pic_url, start_price, reserve_price, duration, left_num';
		$criteria->order = 'display_order desc, t.ctime desc';
		$criteria->limit = 20;
		$criteria->condition = sprintf('status=%d and wap=1', UserAuctionPool::STATUS_ONLINE);
		$criteria->with = array(
			'item' => array('select' => 'title, pic_cover, oprice')
		);
		$itemList = UserAuctionPool::model()->findAll($criteria);

		//取出我创建的血战
		$criteria = new CDbCriteria;
		$criteria->select = 'id,pool_id, item_id, curr_price, reserve_price';
		$criteria->condition = sprintf('uid=%d and status=%d', $user->id, UserAuction::STATUS_ONLINE);
		$auctionList =	UserAuction::model()->findAll($criteria);
		$myPool = array();
		foreach($auctionList as $au){
			$tmp = $this->modelToArray($au);
			$myPool[$au->pool_id] = $au->id;
		}
		//取出我创建的血战的某中一条
		if(count($auctionList) > 0){
			$last = array_pop($auctionList);
			$mykill = $this->modelToArray($last);
			$item = Item::model()->findByPk($mykill['item_id'], array('select' => 'title,pic_cover'));
			$mykill['item'] = $this->modelToArray($item);
		}

		$data = array();
		foreach($itemList as $item){
			$tmp = $this->modelToArray($item);
			$tmp['item']['pic_cover'] = $this->getImageBySize($tmp['item']['pic_cover'], 200);
			$tmp['selfId'] = isset($myPool[$tmp['id']]) ? $myPool[$tmp['id']] : 0;
			//将分钟转成小时
			$tmp['duration'] = ceil($tmp['duration'] / 60);
			$tmp['auctions'] = array();
			$data[] = $tmp;
		}
		$stat['uid'] = $user->id;
		$stat['page'] = 'wap_killend';
		$this->render('/wxkill/killend', array('data' => $data, 'stat' => $stat, 'mykill' => $mykill));
	}

	//我的拍卖列表
	public function actionMy(){
		$user = $this->wechatLogin();
		$criteria = new CDbCriteria;
		$criteria->with = array(
			'item' => array('select' => 'title, pic_cover')
		);
		$criteria->condition = 'uid=' . $user->id;
		$criteria->order = 'start_time desc';
		$auctionList = UserAuction::model()->findAll($criteria);
		$data = array();
		foreach($auctionList as $auction){
			$row = $this->modelToArray($auction);
			$row['item']['pic_cover'] = $this->getImageBySize($row['item']['pic_cover'], 200);
			$row['left_time'] = $auction->getLeftTime();
			$row['start_time'] = date('Y-m-d H:i', $auction->start_time);
			if($row['status'] != UserAuction::STATUS_ONLINE){
				$row['left_time_text'] = UserAuction::$status_list[$row['status']];
			}else{
				$row['left_time_text'] = $this->formatTime($row['left_time']);
			}
			$data[] = $row;
		}

		$stat['uid'] = $user->id;
		$stat['page'] = 'wap_myauction';
		$this->render('/wxkill/myauction', array('data' => $data, 'stat' => $stat));
	}

	//某个拍卖的实况
	public function actionView(){
		$user = $this->wechatLogin();
		$id = $this->getInt('id');
		$criteria = new CDbCriteria;
		$criteria->with = array(
			'item' => array('select' => 'id,title,pic_cover,oprice')
		);
		$ua = UserAuction::model()->findByPk($id, $criteria);
		!$ua && $this->error(111, 'auction not exists');
		$data = $this->modelToArray($ua);
		$data['item']['pic_cover'] = $this->getImageBySize($data['item']['pic_cover'], 200);
		$data['left_time'] = $ua->getLeftTime();
		$data['left_time_text'] = $this->formatTime($ua->getLeftTime());
		$data['share_text'] = $ua->getShareText();
		$data['share_result_text'] = $ua->getShareResultText();
		$data['canBuy'] = !$data['reserve_order'] || $data['curr_price'] == $data['reserve_price'] ? 1 : 0;
		$data['helper_count'] = $ua->help_num;
		
		$criteria = new CDbCriteria;
		$criteria->select = 'uid,open_id,source,discount,ctime';
		$criteria->condition = "auction_id=" . $id;
		$criteria->order = 'ctime desc';
		$criteria->limit = 30;
		$helpers = UserAuctionHelper::model()->with('user')->findAll($criteria);
		$helpers = UserAuctionHelper::model()->mapUser($helpers);
		$data['helpers'] = array();
		foreach($helpers as $helper){
			$helper->ctime = $this->timeAgo($helper->ctime);
			$data['helpers'][] = $this->modelToArray($helper);
		}
		$stat['uid'] = $user->id;
		$stat['page'] = 'wap_userauction';
		$stat['auction_id'] = $id;
		$stat['item_id'] = $data['item_id'];
		$this->render('/wxkill/live', array('data' => $data, 'user' => $user, 'stat' => $stat));
	}

	//创建新拍卖
	public function actionCreate(){
		$user = $this->wechatLogin();
		$uid = $user->id;
		$criteria = new CDbCriteria;
		$criteria->with = array(
			'item' => array('select' => 'title, pic_cover, oprice')
		);
		$poolId = $this->postInt('pool_id');
		$pool = UserAuctionPool::model()->findByPk($poolId, $criteria);
		$user = User::model()->findByPk($user->id, array('select' => 'score'));
		$costScore = abs(UserScore::$score[UserScore::ACT_CREATE_AUCTION]);
		if(!$pool){
			$this->error(101, '参数错误');
		}
		if($user->score < $costScore){
			$data['code'] = 1100;
			$data['costScore'] = $costScore;
			$data['userScore'] = $user->score;
			$data['msg'] = sprintf('创建拍卖需要%d积分，您的积分不够', $costScore);
			$this->renderJSON($data);
		}
		$criteria = new CDbCriteria;
		$criteria->select = 'id';
		$criteria->condition = sprintf("pool_id=%d and uid=%d and status=%d", $poolId, $uid, UserAuction::STATUS_ONLINE);
		$auction = UserAuction::model()->find($criteria);
		if($auction){
			$this->success(array('id' => $auction->id));
		}
		if($pool->left_num == 0){
			$this->error(1102, '您来晚了，商品已经被抢光啦');
		}
		if($pool->status == UserAuctionPool::STATUS_FINISH){
			$this->error(1103, '商品活动已经结束');
		}
		$trans = Yii::app()->db->beginTransaction();
		try{

			$model = new UserAuction;
			$model->item_id = $pool->item_id;
			$model->start_price = $pool->start_price;
			$model->reserve_price = $pool->reserve_price;
			$model->curr_price = $pool->start_price;
			$model->price_interval = $pool->price_interval;
			$model->wechat_price_interval = $pool->wechat_price_interval;
			$model->reserve_order = $pool->reserve_order;
			$model->tip_msg = $model->reserve_order == 1 ? '该商品必须杀到底价才能购买哦' : '赶快分享到朋友圈让好友帮拍吧';
			$model->pool_id = $pool->id;
			$model->uid = $uid;
			$model->start_time = time();
			$model->duration = $pool->duration;
			$model->status = UserAuction::STATUS_ONLINE;
			$model->can_use_coupon = $pool->can_use_coupon;
			$model->can_use_prop = $pool->can_use_prop;
			$model->source = UserAuction::SRC_WECHAT;
			$model->insert();

			//发一条到拍圈
			$json['auction_id'] = $model->id;
			$json['item_id'] = $model->item_id;
			$json['title'] = $model->item->title;
			$json['pic_cover'] = $model->item->pic_cover;
			Circle::postNewAuction($uid, $json);

			$trans->commit();

			//创建拍卖要减积分
			UserScore::add($uid, UserScore::ACT_CREATE_AUCTION);

			$data['id'] = $model->id;
			//标识是新创建的
			$data['is_new'] = 1;
			$this->success($data);
		}catch(Exception $e){
			echo $e->getMessage();
			$trans->rollback();
			$this->error(1104, '拍卖创建失败，请重试');
		}
	}

	public function actionReturnScore(){
		$user = $this->wechatLogin();
		$auctionId = $this->getInt('id');
		$auction = UserAuction::model()->findByPk($auctionId, array('select' => 'id,uid,score_returned'));
		if(!$auction || $auction->uid  != $uid){
			$this->error(101, '参数错误');
		}
		if($auction->score_returned == 1){
			$this->error(1120, '您的血战积分已返还过了');
		}
		$score = UserScore::add($uid, UserScore::ACT_KILLEND_RETURN);
		if($score !== false){
			$auction->score_returned = 1;
			$auction->update(array('score_returned'));
			$data['score'] = $score;
			$data['msg'] = '分享战果成功，返还' . $score . '积分';
			$this->success($data);
		}
		$this->error(112, '数据更新失败，请重试');
	}
}
