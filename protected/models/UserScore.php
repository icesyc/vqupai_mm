<?php

/**
 * This is the model class for table "user_score".
 *
 * The followings are the available columns in table 'user_score':
 * @property string $id
 * @property string $uid
 * @property string $score
 * @property string $action
 * @property string $cdate
 * @property string $ctime
 */
class UserScore extends CActiveRecord
{
	//注册
	const ACT_REGISTER = 1;
	//签到
	const ACT_SIGN = 2;
	//分享
	const ACT_SHARE = 3; 
	//评论
	const ACT_COMMENT = 4;
	//喜欢
	const ACT_LIKE = 5;
	//不喜欢
	const ACT_DISLIKE = 6;
	//晒单
	const ACT_SHOW_ORDER = 7;
	//购买
	const ACT_BUY = 8;
	//创建拍卖
	const ACT_CREATE_AUCTION = 9;
	//底价拍到商品
	const ACT_GET_RESERVE_PRICE = 10;
	//血战返还
	const ACT_KILLEND_RETURN = 11;

	//每天可获得的最大积分
	const MAX_DAY_SCORE = 19;

	public static $score = array(
		self::ACT_REGISTER => 30,
		self::ACT_SIGN => 2,
		self::ACT_SHARE => 5,
		self::ACT_COMMENT => 5,
		self::ACT_LIKE => 1,
		self::ACT_DISLIKE => 1,
		self::ACT_SHOW_ORDER => 15,
		self::ACT_BUY => 10,
		self::ACT_GET_RESERVE_PRICE => 10,
		self::ACT_CREATE_AUCTION => -30,
		self::ACT_KILLEND_RETURN => 30
	);

	public static $action_list = array(
		self::ACT_REGISTER => '注册',
		self::ACT_SIGN => '签到',
		self::ACT_SHARE => '分享',
		self::ACT_COMMENT => '评论',
		self::ACT_LIKE => '喜欢',
		self::ACT_DISLIKE => '不喜欢',
		self::ACT_SHOW_ORDER => '晒单',
		self::ACT_BUY => '购买',
		self::ACT_GET_RESERVE_PRICE => '底价购买',
		self::ACT_CREATE_AUCTION => '创建拍卖',
		self::ACT_KILLEND_RETURN => '血战返还'
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_score';
	}

	//注册，创建拍卖，购买，血战返还不受每日上限
	public static function isLimit($act){
		$noLimitList = array(self::ACT_REGISTER, self::ACT_CREATE_AUCTION, self::ACT_BUY, self::ACT_KILLEND_RETURN);
		foreach($noLimitList as $k){
			$noLimitText[] = self::$action_list[$k];
		}
		return is_int($act) ? !in_array($act, $noLimitList) : !in_array($act, $noLimitText);
	}

	/**
	 * 增加用户积分
	 *
	 * @param int $uid 用户id
	 * @param int $action 加积分的动作
	 * @param int $score 要增加的积分
	 * @return 实际增加的积分
	 */
	public static function add($uid, $action, $score=0){
		if(!isset(self::$score[$action])){
			throw new Exception('invalid action');
		}
		$trans = Yii::app()->db->beginTransaction();
		try{
			$addScore = $score > 0 ? $score : self::$score[$action];
			//购买,创建拍卖,注册不受积分限制
			if(self::isLimit($action)){
				$totalScore = self::getDayScore($uid);
				if($totalScore >= self::MAX_DAY_SCORE){
					throw new Exception('您今天所获得的积分已经达到上限', 111);
				}
				//增加的积分不能超过上限
				$addScore = min(self::MAX_DAY_SCORE - $totalScore,  $addScore);
			}
			$us = new self;
			$us->uid = $uid;
			$us->score = $addScore;
			$us->action = self::$action_list[$action];
			$us->cdate = date('Y-m-d');
			$us->ctime = time();
			$us->insert();

			//更新用户的积分
			User::model()->updateCounters(array('score' => $us->score), "id=$uid");
			$trans->commit();
			return $addScore;

		}catch(Exception $e){
			$trans->rollback();
			return false;
		}
	}

	/**
	 * 判断某用户获得的积分是否已经超过每日最大积分
	 */
	public static function getDayScore($uid){
		$criteria = new CDbCriteria;
		$criteria->select = 'score,action';
		$criteria->condition = 'uid=:uid and cdate=:cdate';
		$criteria->params = array(':uid' => $uid, ':cdate' => date('Y-m-d'));
		$scoreList = self::model()->findAll($criteria);
		$dayScore = 0;
		foreach($scoreList as $row){
			//去掉不受限制的积分类型
			if(self::isLimit($row->action)){
				$dayScore += $row->score;
			}
		}
		return $dayScore;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserScore the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
