<?php
/**
 * 积分说明
 *
 * @author icesyc
 */

class ScoreRuleController extends Controller
{

	public function actionIndex(){
		$user = $this->wechatLogin();

		$rule = array(
			sprintf('注册送%d积分;', UserScore::$score[UserScore::ACT_REGISTER]),
			sprintf('评论%d积分;', UserScore::$score[UserScore::ACT_COMMENT]),
			sprintf('分享%d积分(血战到底的分享不给积分，未成交的血战结束后分享可返回创建血战时消耗的积分);', UserScore::$score[UserScore::ACT_SHARE]),
			sprintf('购买商品%d积分;', UserScore::$score[UserScore::ACT_BUY]),
			sprintf('以底价购买到商品%d积分;', UserScore::$score[UserScore::ACT_GET_RESERVE_PRICE]),
			sprintf('晒单%d积分;', UserScore::$score[UserScore::ACT_SHOW_ORDER]),
			sprintf('创建血战需要%d积分;', abs(UserScore::$score[UserScore::ACT_CREATE_AUCTION])),
			sprintf('每日可获得的积分上限为%d积分(注册、购买和血战返还的积分不受此限制);', UserScore::MAX_DAY_SCORE)
		);
		
		$stat['uid'] = $user->id;
		$stat['page'] = 'wap_scorerule';	
		$this->render('/wxkill/score_rule', array('data' => $rule, 'stat' => $stat));		
	}
}
