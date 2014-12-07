<?php

/**
 * This is the model class for table "user_auction_helper".
 *
 * The followings are the available columns in table 'user_auction_helper':
 * @property string $id
 * @property string $auction_id
 * @property string $uid
 * @property string $open_id
 * @property string $open_nick
 * @property string $open_avatar
 * @property integer $source
 * @property string $discount
 * @property string $ctime
 */
class UserAuctionHelper extends CActiveRecord
{
	const SRC_APP = 1; //APP内帮拍
	const SRC_WECHAT_G = 2; //微信群帮拍
	const SRC_WEIBO = 3;  //微博
	const SRC_SYSTEM = 4; //系统帮拍
	const SRC_WECHAT_S = 5; //微信好友帮拍
	const SRC_WECHAT_T = 6; //朋友圈帮拍

	const MAX_DAY_HELP = 5;

	public static $nick_list = array(
		self::SRC_APP => '微趣拍网友',
		self::SRC_WECHAT_G => '微信好友',
		self::SRC_WEIBO => '微博网友',
		self::SRC_SYSTEM => '系统帮拍',
		self::SRC_WECHAT_S => '微信好友',
		self::SRC_WECHAT_T => '微信好友',
	);

	public static $avatar_list = array(
		self::SRC_APP => '/upic/defavatar1.jpg',
		self::SRC_WECHAT_G => '/upic/wx_avatar.png',
		self::SRC_WEIBO => '/upic/wb.png',
		self::SRC_SYSTEM => '/upic/vqupai.png',
		self::SRC_WECHAT_S => '/upic/wx_avatar.png',
		self::SRC_WECHAT_T => '/upic/wx_avatar.png',
	);
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_auction_helper';
	}

	//映射用户
	public function mapUser($helpers){
		$openList = array();
		foreach($helpers as $row){
			//有用户的不做处理
			if($row->user){
				continue;
			}
			if($row->open_id){
				$openList[] = $row->open_id;
			}
		}
		$criteria = new CDbCriteria;
		$criteria->addInCondition('open_id', $openList);
		$criteria->addCondition(sprintf("platform='%s'", UserPlatformBind::PLAT_WECHAT_MP));
		$criteria->with = 'user';
		$criteria->select = 'open_id';
		$platList = UserPlatformBind::model()->findAll($criteria);
		$platMap = array();
		foreach($platList as $plat){
			$platMap[$plat->open_id] = $plat->user;
		}
		foreach($helpers as $row){
			if($row->user){
				continue;
			}
			if(isset($platMap[$row->open_id])){
				$row->user = $platMap[$row->open_id];
				continue;
			}
			$user = new User;
			$user->unsetAttributes();
			$user->nick = self::$nick_list[$row->source];
			$user->avatar = self::$avatar_list[$row->source];
			$row->user = $user;
			unset($row->uid, $row->open_id);
		}
		return $helpers;
	}

	//格式化成标准格式的用户
	public function getUser(){
		if($this->user){
			return $this->user;
		}
		$user = new User;
		$user->unsetAttributes();
		$user->id = 0;
		$user->nick = $this->open_nick;
		$user->avatar = $this->open_avatar;

		if(!$user->nick){
			$user->nick = self::$nick_list[$this->source];
		}
		if(!$user->avatar){
			$user->avatar = self::$avatar_list[$this->source];
		}
		return $user;
	}

	public static function canHelp($helperId, $auction){
		//是否过期或者结束了
		if(!$auction){
			throw new Exception('拍卖不存在', 1105);
		}
		if($auction->getLeftTime() == 0){
			throw new Exception('拍卖已结束', 1106);
		}
		//如果是黑名单，直接返回
		if(!$helperId || KillendBl::has($helperId)){
			throw new Exception('帮拍失败', 1107);
		}
		//对帮拍ip进行限制
		$ip = $_SERVER['REMOTE_ADDR'];
		$ipConfig = Config::getKV('WechatHelpIp');
		$ipcnt = self::model()->count(sprintf("auction_id=%d and ip='%s'", $auction->id, $ip));
		$percent = $auction->help_num > 0 ? $ipcnt / $auction->help_num : 0;
		if($auction->help_num > $ipConfig['wechat_ip_num'] && $percent > $ipConfig['wechat_ip_percent']){
			Yii::log(sprintf('%s is blocked, cnt=%d', $ip, $ipcnt));
			throw new Exception('帮拍失败', 1107);
		}

		$exists = self::model()->exists(sprintf("auction_id=%d and open_id='%s'", $auction->id, $helperId));
		if($exists){
			throw new Exception("您已经帮忙拍过了", 1108);
		}
		//如果白名单，不限制帮拍次数
		if($helperId && KillendWl::has($helperId)){
			return true;
		}
		/*$stat = HelperStat::get($helperId);
		$maxDayHelp = Config::get('wechat_max_day_help');
		if($stat && $stat->day_num >= $maxDayHelp){
			throw new Exception('您今天已经帮拍过' . $maxDayHelp . '次了', 1109);
		}*/
		return true;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'uid', 'select' => 'id,nick,avatar')
		);
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserAuctionHelper the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
