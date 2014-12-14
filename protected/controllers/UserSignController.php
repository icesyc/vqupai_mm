<?php
class UserSignController extends Controller {
	//public $layout='//layouts/userSign';

	public function actionIndex()
	{
		if(!$_GET['token'])
			$_GET['token'] = '';

		//如果没有登陆，渲染去登陆页面
		/*if(!$uid=$this->initUser($_GET['token'])){
			$this->render('need_login', array('token'=>$_GET['token']));
			Yii::app()->end();
		}*/

		//如果已经登陆，拉取数据
		$month = date('Ym', time());
		var_dump($month);
		$data = array();
		$data['user'] = array();
		$data['sign_info'] = array();

		$coupon_text = Coupon::model()->getAllText();
		$prop_text = Prop::model()->getAllText();
		$sign_info = SignInfo::model()->getSignInfoMonth($month);
		$data['sign_info']['days'] = count($sign_info);
		$i = 0;
		foreach($sign_info as $r){
			($r['exp']) != 0 ? $data['sign_info']['info'][$i]['exp']=$r['exp'].'经验值' : $data['sign_info']['info'][$i]['exp']='';
			($r['score']) != 0 ? $data['sign_info']['info'][$i]['score']=$r['score'].'积分' : $data['sign_info']['info'][$i]['score']='';
			($r['coupon']) != 0 ? $data['sign_info']['info'][$i]['coupon']=$coupon_text[$r['coupon']] : $data['sign_info']['info'][$i]['coupon']='';
			($r['prop']) != 0 ? $data['sign_info']['info'][$i]['prop']=$prop_text[$r['prop']] : $data['sign_info']['info'][$i]['prop']='';
			$i++;
		}
		var_dump($data);exit;

		$this->render('index', array(
			'token'=>$_GET['token'],
			'err_msg'=>$err_msg,
			'data'=>$data,
			));
	}

	public function actionMain()
	{
		//如果未登陆，调用app登陆接口
		if (!$uid=$this->initUser($_GET['token'])) {
			echo "<script>setTimeout(function(){top.postMessage('login','*');}, 1000);</script>";
			Yii::app()->end();
		}

		if(isset($_GET['err_msg']))
			$err_msg = $_GET['err_msg'];
		else
			$err_msg = '';

		//如果已经登陆，查询用户是否有手机号，显示填写手机号页面
		//$uid = 100002;
		$user = User::model()->findByPk($uid);
		if($user) {
			if($user->phone)
				$phone = $user->phone;
		}
		else
			$phone = '';

		if(!isset($_GET['token']))
			$_GET['token'] = '';
		$this->render('main', array(
			'token' => $_GET['token'],
			'phone' => $phone,
			'err_msg'=>$err_msg,
			));
	}

	public function actionCheck()
	{
		//如果未登陆，调用app登陆接口
		if (!$uid=$this->initUser($_GET['token'])) {
			echo "<script>setTimeout(function(){top.postMessage('login','*');}, 1000);</script>";
			Yii::app()->end();
		}

		//$uid = 100002;

		if(isset($_GET['err_msg']))
			$err_msg = $_GET['err_msg'];
		else
			$err_msg = '';
		if(!$_GET['token'])
			$_GET['token'] = '';
		$token = $_GET['token'];

		//如果没有phone,返回上一页
		if(isset($_GET['phone'])) {
			$phone = $_GET['phone'];
			if(empty($phone))
				$this->redirect(array('main', 'token'=>$token, 'err_msg'=>'请输入手机号'));
			elseif (!is_numeric($phone) || strlen($phone)!=11 )
				$this->redirect(array('main', 'token'=>$token, 'err_msg'=>'请正确输入手机号'));
			elseif (!$this->phoneValidator($phone))
				$this->redirect(array('main', 'token'=>$token, 'err_msg'=>'您输入的真的是手机号吗？'));
			else {
				$cri = new CDbCriteria;
				$cri->condition = sprintf('(uid = %d) or phone = "%s"', $uid, $phone);
				$ck = ActIphone::model()->find($cri);
				if($ck) {
					$this->redirect(array('main', 'token'=>$token, 'err_msg'=>'亲，您已经参加过啦～'));
				}
				else {
					$in = new ActIphone;
					$in->uid = $uid;
					$in->phone = $phone;
					$in->ctime = time();
					$in->insert();
				}
			}
		}

		$this->render('check', array(
			'token' => $_GET['token'],
			'err_msg'=>$err_msg,
			//'phone' => $phone,
			));
	}



	//初始化用户信息,如果成功就返回用户id
	public function initUser($token=''){
		Yii::app()->setComponents(array(
			'user' => array(
				'class'=>'CWebUser',
				'stateKeyPrefix'=>'app',
				'allowAutoLogin' => false,  //不启用cookie验证
				'authTimeout' => 86400 * 7, //登录状态7天过期
				'loginUrl' => null
				),
			'session' => array(
				'autoStart' => false, //不自动开始session，否则不能手动设置session_id
				'timeout' => 86400 * 8, //要比authTimeout长一些
				'cookieMode' => 'none'  //不启用cookie
				),
		));

		if($token == '') {
			return false;
		}
		else {
			$token = trim($token);
			//将当前的token设置成session
			Yii::app()->getSession()->setSessionID($token);
			$uid = Yii::app()->user->getId();
			if(!$uid) {
				return false;
			}
			else {
				return $uid;
			}
		}
	}
}
?>