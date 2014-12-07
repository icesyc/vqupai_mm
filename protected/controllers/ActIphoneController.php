<?php
class ActIphoneController extends Controller {
	public $layout='//layouts/actIphone';

	public function actionIndex()
	{
		if(isset($_GET['err_msg']))
			$err_msg = $_GET['err_msg'];
		else
			$err_msg = '';

		if(!$_GET['token'])
			$_GET['token'] = '';

		$this->render('index', array(
			'token'=>$_GET['token'],
			'err_msg'=>$err_msg,
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

	//验证手机号码
	private function phoneValidator($phone) {
		$opratorHash=array(
			'cmcc'=>'中国移动',
			'cucc'=>'中国联通',
			'ctcc'=>'中国电信',
			'vop'=>'虚拟运营商',
			'satellite'=>'卫星电话',
		);
		$referHash=array(
			'130'=>'cucc',
			'131'=>'cucc',
			'132'=>'cucc',
			'133'=>'ctcc',
			'134'=>'cmcc',
			'1349'=>'satellite',
			'135'=>'cmcc',
			'136'=>'cmcc',
			'137'=>'cmcc',
			'138'=>'cmcc',
			'139'=>'cmcc',
			'145'=>'cucc',
			'147'=>'cmcc',
			'150'=>'cmcc',
			'151'=>'cmcc',
			'152'=>'cmcc',
			'153'=>'ctcc',
			'155'=>'cucc',
			'156'=>'cucc',
			'157'=>'cmcc',
			'158'=>'cmcc',
			'159'=>'cmcc',
			'170'=>'vop',
			'176'=>'cucc',
			'177'=>'ctcc',
			'178'=>'cmcc',
			'180'=>'ctcc',
			'181'=>'ctcc',
			'182'=>'cmcc',
			'183'=>'cmcc',
			'184'=>'cmcc',
			'185'=>'cucc',
			'186'=>'cucc',
			'187'=>'cmcc',
			'188'=>'cmcc',
			'189'=>'ctcc',
		);
		$segment3=substr($phone, 0, 3);
		$segment4=substr($phone, 0, 4);
		$ret=false;
		if (!isset($referHash[$segment3])) {
			return false;
		}
		if ($segment4=='1349') {
			$ret=$referHash[$segment4];
		} else {
			$ret=$referHash[$segment3];
		}
		return $ret;
	}
}
?>