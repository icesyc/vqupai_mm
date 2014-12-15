<?php
class ActShihuiController extends Controller {
	public $layout='//layouts/actShihui';

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

		if(!isset($_GET['token']))
			$_GET['token'] = '';

		//检查verify_code
		if($_POST) {
			if(isset($_POST['verify_code']))
			{
				$verify_code = $_POST['verify_code'];
				$cri = new CDbCriteria;
				$cri->condition = sprintf('verify_code = %d', $verify_code);
				$ck = ActShihui::model()->find($cri);
				if(!$ck) {
					#没有这个验证码
					$err_msg = '验证码错误！请重新输入！';
				}
				elseif ($ck->uid!=0) {
					#验证码已经被使用
					$err_msg = '此验证码已经被使用，请检查！';
				}
				else {
					#正常领用
					$this->redirect(array('consignee', 
						'verify_code' => $verify_code,
						'token' => $_GET['token'],
						'err_msg'=>$err_msg,
						));
				}
			}
			else {
				$err_msg = '请输入验证码！';
			}
		}

		$this->render('main', array(
			'token' => $_GET['token'],
			'err_msg'=>$err_msg,
			));
	}

	public function actionConsignee()
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
		if(!isset($_GET['token']))
			$_GET['token'] = '';

		$verify_code = $_GET['verify_code'];
		if(isset($_POST['consignee'])) {
			$consignee = $_POST['consignee'];
			//var_dump($_POST);
			if(empty($_POST['consignee']['name'])) {
				$err_msg = '请填写收货人姓名！';
			}
			elseif (empty($_POST['consignee']['province'])) {
				$err_msg = '请选择省份！';
			}
			elseif (empty($_POST['consignee']['city'])) {
				$err_msg = '请选择城市！';
			}
			elseif (empty($_POST['consignee']['address'])) {
				$err_msg = '请填写详细地址！';
			}
			elseif (empty($_POST['consignee']['mobile'])) {
				$err_msg = '请填写手机号！';
			}
			elseif (!is_numeric($_POST['consignee']['mobile']) || strlen($_POST['consignee']['mobile'])!=11 ) {
				$err_msg = '手机号必须为11位数字！';
			}
			elseif (!$this->phoneValidator($_POST['consignee']['mobile'])) {
				$err_msg = '请正确填写手机号！';
			}
			else {

				$cri = new CDbCriteria;
				$cri->condition = sprintf('verify_code = %d', $verify_code);
				$in = ActShihui::model()->find($cri);
				$in->uid = $uid;
				$in->name = $_POST['consignee']['name'];
				$in->province = $_POST['consignee']['province'];
				$in->city = $_POST['consignee']['city'];
				$in->address = $_POST['consignee']['address'];
				$in->mobile = $_POST['consignee']['mobile'];
				$in->ctime = time();
				$in->update();

				//写user_consignee
				$cri = new CDbCriteria;
				$cri->condition = sprintf('uid=%d and is_default=1', $uid);
				$ck = UserConsignee::model()->find($cri);
				if($ck) {
					$is_default = 0;
				}
				else {
					$is_default = 1;
				}
				$co = new UserConsignee;
				$co->uid = $uid;
				$co->name = $_POST['consignee']['name'];
				$co->province = $_POST['consignee']['province'];
				$co->city = $_POST['consignee']['city'];
				$co->address = $_POST['consignee']['address'];
				$co->mobile = $_POST['consignee']['mobile'];
				$co->is_default = $is_default;
				$co->insert();


				$this->redirect(array('success', 
						'token' => $_GET['token'],
						'err_msg'=>$err_msg,
						));
			}
		}
		else {
			$consignee = array(
				'verify_code' => $verify_code,
				'name' => '',
				'province' => '',
				'city' => '',
				'address' => '',
				'mobile' => '',
				);
		}

		$this->render('consignee', array(
			'verify_code' => $verify_code,
			'consignee' => $consignee,
			'token' => $_GET['token'],
			'err_msg'=>$err_msg,
			));
	}

	public function actionSuccess()
	{
		//如果未登陆，调用app登陆接口
		if (!$uid=$this->initUser($_GET['token'])) {
			echo "<script>setTimeout(function(){top.postMessage('login','*');}, 1000);</script>";
			Yii::app()->end();
		}

		$uid=100002;

		if(isset($_GET['err_msg']))
			$err_msg = $_GET['err_msg'];
		else
			$err_msg = '';
		if(!isset($_GET['token']))
			$_GET['token'] = '';

		$this->render('success', array(
			'token' => $_GET['token'],
			'err_msg'=>$err_msg,
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