<?php
/**
 * 内部人员登记open_id
 */

Yii::import('application.extensions.wechat.Wechat');
class WechatWlController extends Controller
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

	public function actionIndex(){
		$err_msg = '';
		$code = 2014;

		if($_POST) {
			if(empty($_POST['name']) or empty($_POST['code'])){
				$err_msg = '请填写完整信息！';
			}
			elseif ($_POST['code'] != $code) {
				$err_msg = '验证码错误！';
			}
			else {
				$cri = new CDbCriteria;
				$cri->condition = sprintf('open_id = "%s"', Yii::app()->session['open_id']);
				$ck = KillendWl::model()->find($cri);
				if($ck) {
					$err_msg = '此id已经存在，请不要重复录入！';
				}
				else {
					$in = new KillendWl;
					$in->open_id = Yii::app()->session['open_id'];
					$in->name = $_POST['name'];
					$in->ctime = time();
					$in->insert();
					$err_msg = '您的信息已经记录！<br>请关闭页面！<br>谢谢！';
				}
			}
		}
		//var_dump(parse_str($_SERVER['REQUEST_URI'],'&'));exit;
		//var_dump($_SERVER['REQUEST_URI']);exit;
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

		$this->render('index',array(
			'err_msg' => $err_msg,
			'from'=>$from,
			));
	}

}
