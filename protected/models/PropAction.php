<?php
/**
 * 道具动作应用列表 分两类 一种是apply，对对象应用道具，直接产生效果，或产生持续效果
 * 一种是持续效果的回调handler,每次减价都会调用handler, 对减价的效果进行叠加
 *
 * @author icesyc 2014.7.19
 */

class PropAction {

	/**
	 * 对某个对象应用道具
	 * 
	 * @param object $prop 要应用的道具对象
	 * @param object $target 要应用的对象
	 * @param mix $param 应用的参数
	 * @return mix 应用成功时返回target, 失败返回false
	 */
	public static function apply($prop, $target, $param=null){
		$action = $prop->action;
		if(!$action) return $target;
		$action = 'apply' . ucfirst($action);
		if(method_exists(__CLASS__, $action)){
			return self::$action($prop, $target, $param);
		}
		return false;
	}	

	/**
	 * 对某个对象执行道具产生的效果
	 * 
	 * @param object $prop 要执行的道具对象
	 * @param object $target 要执行的目标对象
	 * @param mix $param 执行时的参数
	 * @return object $target 返回执行道具的目标对象
	 */
	public static function run($prop, $target, $param=null){
		$action = $prop->action;
		if(!$action) return $target;
		$action = $action . 'Handler';
		if(method_exists(__CLASS__, $action)){
			return self::$action($prop, $target, $param);
		}
		return $target;
	}

	/**
	 * 延时卡, 对拍卖使用，可以增加拍卖的持续时间2倍
	 * 一次性道具，没有持续时间
	 *
	 * @param object $prop 道具对象
	 * @param object $target 拍卖对象
	 * @param mix $param 参数
	 * @return 成功时返回拍卖对象，失败返回false
	 */
	public static function applyExtendDuration($prop, $target, $param){
		if(!($target instanceof UserAuction)){
			Yii::log('target is not a UserAuction instance', 'error');
			return false;
		}
		$target->duration *= 2;
		return $target->update(array('duration')) ? $target : false;
	}

	//应用双倍卡，不需要做任何操作
	public static function applyDoubleDiscount($prop, $target, $param){
		if(!($target instanceof UserAuction)){
			Yii::log('target is not a UserAuction instance', 'error');
			return false;
		}
		return $target;
	}

	/**
	 * 双倍减价卡, 使用后拍卖的所有帮拍减价都将变成双倍
	 *
	 * @param object $prop 道具对象
	 * @param object $target auction对象
	 * @param array $state 减价相关的状态对象  [discount 减价的初始值, uid 减价的用户id, finalDiscount 最终的减价值]
	 * @return 拍卖对象
	 */
	public static function doubleDiscountHandler($prop, $target, $state){
		if(!($target instanceof UserAuction)){
			Yii::log('target is not a UserAuction instance', 'error');
			return false;
		}
		$state->finalDiscount += $state->discount;
		return $target;
	}
}