<?php
/*
 * 用户的道具列表接口
 *
 */

class MyPropController extends Controller
{

	public function actionIndex(){
		$user = $this->wechatLogin();
		$auctionId = $this->getInt('auction_id');

		if(!$auctionId){
			$this->error(111, '参数错误');
		}

		$user = $this->wechatLogin();
		$uid = $user->id;
		$critera = new CDbCriteria;
		$critera->with = array(
			'prop' => array('select' => 'name, description, action')
		);
		$critera->condition = 'num > 0 and uid=' . $uid;
		$propList = UserProp::model()->findAll($critera);

		$stat['uid'] = $user->id;
		$stat['page'] = 'wap_myprop';
		$this->render('/wxkill/prop', array('propList' => $propList, 'auction_id' => $auctionId, 'stat' => $stat));
	}

	public function actionUse(){
		$user = $this->wechatLogin();
		$uid = $user->id;
		$propId = $this->getInt('prop_id');
		$auctionId = $this->getInt('auction_id');
		$auction = UserAuction::model()->findByPk($auctionId);

		if(!$auction->can_use_prop){
			$this->error(1203, '该拍卖不可使用道具');
		}

		if($auction->status != UserAuction::STATUS_ONLINE){
			$this->error(1202, '该拍卖已经结束了');
		}
		$up = UserProp::model()->with('prop')->findByAttributes(array('uid' => $uid, 'prop_id' => $propId));
		if(!$up || $up->num == 0){
			$this->error(1201, '您还没有该道具，请先用积分兑换');
		}

		$exists = UserAuctionProp::model()->exists(sprintf('auction_id=%d and prop_id=%d', $auctionId, $propId));
		$exists && $this->error(1201, '该拍卖已经应用了该道具');
		if(!$up->applyProp($auction)){
			$this->error(111, '道具应用失败');
		}else{
			$msg = array('', '您的拍卖时间延长了一倍', '您的拍卖在2小时内将获得双倍减价的效果');
			$data['msg'] = $msg[$propId];
			$this->success($data);	
		}
	}
}