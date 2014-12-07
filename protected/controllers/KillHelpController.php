<?php
/**
 * 血战说明
 *
 * @author icesyc
 */

class KillHelpController extends Controller
{

	public function actionIndex(){
		$user = $this->wechatLogin();

		$stat['uid'] = $user->id;
		$stat['page'] = 'wap_killhelp';	

		$this->render('/wxkill/kill_help', array('stat' => $stat));		
	}
}
