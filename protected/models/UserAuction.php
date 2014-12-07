<?php

/**
 * This is the model class for table "user_auction".
 *
 * The followings are the available columns in table 'user_auction':
 * @property string $id
 * @property string $pool_id
 * @property string $uid
 * @property string $item_id
 * @property string $start_price
 * @property string $curr_price
 * @property string $reserve_price
 * @property string $price_interval
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 * @property string $help_num
 */
class UserAuction extends CActiveRecord
{
	const STATUS_ONLINE = 1; //进行中
	const STATUS_FINISH = 2; //拍卖已结束，等待用户下单
	const STATUS_DEAL = 3; //已成交
	const STATUS_CANCEL = 4; //已取消

	const SRC_APP = 0;	//来源 app
	const SRC_WECHAT = 1; //来源 微信
	
	public static $status_list = array(
		self::STATUS_ONLINE => '进行中',
		self::STATUS_FINISH => '等待购买',
		self::STATUS_DEAL => '已成交',
		self::STATUS_CANCEL => '已取消'
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_auction';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'item' => array(self::BELONGS_TO, 'Item', 'item_id', 'joinType' => 'inner join'),
			'user' => array(self::BELONGS_TO, 'User', 'uid', 'joinType' => 'inner join', 'select' => 'id,nick,avatar')
		);
	}

	//返回剩余时间
	public function getLeftTime(){
		if($this->status != self::STATUS_ONLINE){
			return 0;
		}
		return max(0, $this->start_time + $this->duration * 60 - time());
	}

	//获取结束时间
	public function getEndTime(){
		return $this->start_time + $this->duration * 60;
	}

	//返回当前减价的百分比
	public function getProgress(){
		$all = $this->start_price - $this->reserve_price;
		$percent = $this->start_price - $this->curr_price;
		return $all == 0 ? 100 : round($percent / $all * 100, 2);
	}

	//返回本次减价的初始
	public function getDiscount($src, $openId){
		$interval = explode("-", $this->wechat_price_interval);
		$start = max(0, min($interval));
		$end   = max(0, max($interval));
		if($start == 0 && $end == 0){
			$discount = 0;
		}else{
			$discount = mt_rand($start * 100, $end * 100) / 100;
		}
		//return max(0.01, round($discount * $this->getWeight($openId), 2));
		return max(0.01, round($discount * $this->getDayWeight($openId), 2));
	}

	//根据帮拍用户的device信息和被拍用户的信息计算权重
	public function getWeight($device){
		$src = $device;
		$target = $this->puid ? $this->puid : $this->uid;
		if(!$src || !$target){
			$weight = 0.5;
		}else{
			$kw = KillWeight::model()->findByAttributes(array('src' => $src, 'trg' => $target));
			$weight = $kw ? $kw->weight / 100 : 1;
		}
		return $weight;
	}

	//根据用户openId获取该id当天帮杀次数，对照config确定权重
	public function getDayWeight($openId){
		//如果从来没有帮杀，1
		$stat = HelperStat::get($openId);
		if(!$stat){
			$ret = 1;
		}
		else{
			//如果在白名单里，1
			if(KillendWl::has($openId)){
				$ret = 1;
			}
			else {
				$day_num = $stat->day_num;
				$weight_rules = Config::getKV('KillDayTimesRule');
				foreach($weight_rules as $rule=>$weight){
					$tmp = explode(',', $rule);
					$min = intval($tmp[0]);
					$max = intval($tmp[1]);
					if(($day_num >= $min) and ($day_num <= $max)) {
						$ret = $weight/100;
						break;
					}
				}
			}
		}
		//如果权重大于1，1
		if($ret>1) $ret=1;
		return $ret;
	}

	public function getShareText(){
		$tpl = '人多力量大！各位亲朋好友，快来帮我把它杀到%s元吧！（猛戳这里）';
		return sprintf($tpl, (float)$this->reserve_price);
	}

	public function getShareResultText(){
		$tpl = '我在血战到底中将价格杀至%s元，你也来试试？';
		return sprintf($tpl, (float)$this->curr_price);
	}

	/**
	 * 对该用户拍卖进行帮拍, 先算出帮拍减掉的金额，再对拍卖的所有道具做一次过滤处理得到最终的金额
	 *
	 */
	public function takeHelp($uid, $source=UserAuctionHelper::SRC_APP){
		$now = time();
		$discount = $this->getDiscount($source, $uid);

		//如果是iphone6，每次杀1元
		if($this->pool_id == 66) $discount = 1;

		$activeProp = array();

		//如果帮杀来自微信群，降权至30%，双倍卡无效
		$from = Yii::app()->session['help_from'];
		if($from == 'nothing'){
			$discount = 0;
		}
		elseif($from == 'groupmessage'){
			$discount = max(0.01, round($discount * 0.3, 2));
			$discount = round($discount * 0.3, 2);
		}

		//对拍卖对象检查所有的buff，并应用
		$propList = UserAuctionProp::model()->findAllByAttributes(array('auction_id' => $this->id));
		$state = new stdClass;
		$state->discount = $discount;
		$state->uid = $uid;
		$state->finalDiscount = $discount;
		if($from != 'groupmessage'){
			foreach($propList as $prop){
				//没有持续时间的一次性道具无需处理
				if($prop->duration == 0) continue;
				//道具已经失效
				if($prop->getLeftTime() == 0){
					continue;
				}
				$activeProp[] = $prop->action;
				PropAction::run($prop, $this, $state);			
			}
		}

		$trans = Yii::app()->db->beginTransaction();
		try{
			//减价
			$finalDiscount = min($state->finalDiscount, $this->curr_price - $this->reserve_price);
			$this->curr_price = $this->curr_price - $finalDiscount;
			$this->help_num++;
			$this->last_help_time = time();
			$this->update(array('curr_price', 'help_num', 'last_help_time'));

			//保存帮拍记录
			$helper = new UserAuctionHelper;
			$field = $source == UserAuctionHelper::SRC_APP ? 'uid' : 'open_id';
			$helper->$field = $uid;
			$helper->auction_id = $this->id;
			

			//区分来自群、好友、朋友圈
			switch (Yii::app()->session['help_from']) {
				case 'groupmessage':
					$helper->source = UserAuctionHelper::SRC_WECHAT_G;
					break;
				case 'singlemessage':
					$helper->source = UserAuctionHelper::SRC_WECHAT_S;
					break;
				case 'timeline':
					$helper->source = UserAuctionHelper::SRC_WECHAT_T;
					break;
				default:
					$helper->source = $source;
					break;
			}

			$helper->discount = $finalDiscount;
			$helper->ctime = $now;
			$helper->tuid = $this->uid;
			$helper->tpuid = $this->puid;
			$helper->ip = $_SERVER['REMOTE_ADDR'];
			$helper->insert();
			$trans->commit();
			$res['prop'] = $activeProp;
			$res['discount'] = $finalDiscount;
			$res['curr_price'] = $this->curr_price;
			$res['progress'] = $this->getProgress();
			return $this->onTakeHelp($helper, $res);
		}catch(CDbException $e){
			$trans->rollback();
			return false;
		}
	}

	/**
	 * 帮拍的事件回调, 可用于帮拍成功后的逻辑
	 * 
	 * @param object $helper 帮助人对象
	 */
	public function onTakeHelp($helper, $res){
		//帮拍计数
		$helperId = $helper->source == UserAuctionHelper::SRC_APP ? $helper->device_token : $helper->open_id;
		HelperStat::increase($helperId);

		//通知用户帮拍
		MQ::send($this->uid, MQ::MSG_USER_AUCTION_HELP, $res);
		//到达底价发通知用户
		if($this->curr_price == $this->reserve_price){
			$msg = array('uid' => $this->uid, 'curr_price' => $this->curr_price, 'auction_id' => $this->id);
			MQ::sendSys(MQ::MSG_AUCTION_RESERVE_PRICE, $msg);
		}
		return $res;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserAuction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
