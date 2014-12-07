<?php
/**
 * 消息队列封装类
 * 可以给单个用户发消息，可以给系统队列发消息
 * 消息体结构
 *    type 类型
 *    time 消息产生的时间 unixstamp
 *    content 消息内容 可能是简单类型(int,string)或复合类型(array)
 *
 *  @author icesyc
 */

class MQ {

	//好友请求
	const MSG_FRIEND_REQUEST = 1;
	//新用户消息
	const MSG_MESSAGE = 2;
	//拍圈新内容
	const MSG_CIRCLE = 3;
	//拍圈新回复
	const MSG_CIRCLE_REPLY = 4;
	//拍圈赞
	const MSG_CIRCLE_ZAN = 5;
	//系统更新
	const MSG_APP_UPDATE = 6;

	//系统图像处理
	const MSG_IMAGE = 7;
	//系统价格趋势图
	const MSG_CHART = 8;

	//新拍券
	const MSG_NEW_COUPON = 9;
	//新道具
	const MSG_NEW_PROP = 10;
	//新订单
	const MSG_NEW_ORDER = 11;
	//订单已发货
	const MSG_ORDER_SHIP = 12;

	//闪屏
	const MSG_SPLASH = 13;

	//用户关注
	const MSG_FOLLOW = 14;

	//拍卖开始
	const MSG_AUCTION_START = 15;

	//拍卖结束
	const MSG_AUCTION_FINISH = 16;

	//拍卖到达底价
	const MSG_AUCTION_RESERVE_PRICE = 17;

	//拍卖变价
	const MSG_AUCTION_CHANGE_PRICE = 18;
	
	//有人帮拍
	const MSG_USER_AUCTION_HELP = 19;
	
	//用户的消息队列: user_mq_{uid}
	public static $userPrefix = "user_mq_";
	//系统消息队列 后台daemon程序处理
	public static $systemMq = 'system_mq';

	/**
	 * 给某个用户发消息
	 *
	 * @param int $uid 用户id
	 * @param int $type 消息类型,本类中定义的常量
	 * @param mix $content 消息体
	 *
	 */
	public static function send($uid, $type, $content){
		$qname = self::$userPrefix . $uid;
		$data['type'] = $type;
		$data['time'] = time();
		$data['content'] = $content;
		return Yii::app()->httpsqs->put($qname, $data);
	}

	/**
	 * 从用户的队列中接收一条消息
	 *
	 * @param int $uid 用户id
	 * @return mix 返回的消息内容,失败或队列为空时返回false
	 */
	public static function recv($uid){
		$qname = self::$userPrefix . $uid;
		return Yii::app()->httpsqs->get($qname);
	}

	/**
	 * 发送系统消息
	 *
	 * @param int $type 消息类型
	 * @param mix $content 消息内容
	 */
	public static function sendSys($type, $content){
		$qname = self::$systemMq;
		$data['type'] = $type;
		$data['time'] = time();
		$data['content'] = $content;
		return Yii::app()->httpsqs->put($qname, $data);
	}

	/**
	 *  接收系统消息
	 */
	public static function recvSys(){
		$qname = self::$systemMq;
		return Yii::app()->httpsqs->get($qname);
	}
}