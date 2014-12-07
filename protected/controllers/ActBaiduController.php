<?php
class ActBaiduController extends Controller {
	public $layout="//layouts/baidu";
	public $_sourceUri="/luck/";
	public $_imagesUri="/luck/images/";
	public $_jsUri="/luck/js/";
	public $_cssUri="/luck/css/";

//	public $sessionId=Yii::app()->session->sessionID;
	public $probabilityBase=100000;

	public $user;

	public function actionIndex() {
		if (!$uid=$this->initUser($_GET['token'])) {
			// 直接调用APP登录接口
			//echo "<script>setTimeout(function(){top.postMessage('login','*');}, 1000);</script>";

			$this->render('index', array(
				'token'=>$_GET['token'],
				));
			Yii::app()->end();
		}

		// 主入口
		$this->redirect(array('main', 'token'=>$_GET['token']));
	}

	public function actionMain() {
		if (!$uid=$this->initUser($_GET['token'])) {
			// 直接调用APP登录接口
			echo "<script>setTimeout(function(){top.postMessage('login','*');}, 1000);</script>";
			Yii::app()->end();
		}

		$partinChk=ActivitysepPartin::model()->findByAttributes(array('session_id'=>$uid));
		if ($partinChk) {
			//$this->redirect(array('participated'));
			if ($partinChk->astatus==2 || $partinChk->astatus==3) {
				$this->render('participated', array(
					'token'=>$_GET['token'],
					));
				Yii::app()->end();
			}
			if ($partinChk->astatus==1) {
				$this->redirect(array('success', 'token'=>$_GET['token']));
				Yii::app()->end();
			}
		}

		//Yii::app()->session['aid']=0;
		// 抽奖程序
		$this->render('main', array(
			'token'=>$_GET['token'],
			));
	}

	public function actionSuccess() {
		if (!$uid=$this->initUser($_GET['token'])) {
			// 直接调用APP登录接口
			echo "<script>setTimeout(function(){top.postMessage('login','*');}, 1000);</script>";
			Yii::app()->end();
		}

		$partinChk=ActivitysepPartin::model()->findByAttributes(array('session_id'=>$uid));
		if ($partinChk) {
			//$this->redirect(array('participated'));
			if ($partinChk->astatus==2 || $partinChk->astatus==3) {
				$this->render('participated', array(
					'token'=>$_GET['token'],
					));
				Yii::app()->end();
			}
		}

		// 抽奖结果
		$award=ActivityStock::model()->findByPk($partinChk->aid);
		$awardId = $partinChk->aid;

		if (isset($_POST['phone'])) {
			$phone=$_POST['phone'];
			if (empty($phone)) {
				$error_msg = "请输入手机号";
			}
			elseif (!is_numeric($phone) || strlen($phone)!=11 ) {
					$error_msg = "请正确输入手机号";
				}
			elseif (!$this->phoneValidator($phone)) {
				$error_msg = "您输入的真的是手机号吗？";
			}
			else{
				$chkPhone=ActivitysepPartin::model()->findByAttributes(array('phone'=>$phone));
				if ($chkPhone) {
					$error_msg = "不要重复请输入手机号哦～";
				}
				else{
					$partin=ActivitysepPartin::model()->findByAttributes(array('session_id'=>$uid));
					$partin->phone=$phone;
					$partin->astatus=2;
					$partin->ctime=time();
					

					if($partin->aid == 9) {
						$coupon_id=1004; // 10元拍券
						$model_coupon=Coupon::model()->findByPk($coupon_id);
						$uc=new UserCoupon;
						$uc->uid=$partin->session_id;
						$uc->coupon_id=$coupon_id;
						$uc->num=1;
						$uc->expire_time=$model_coupon->getExpireTime();
						if ($uc->save()) {
							$partin->astatus=3;
						}
					}
					else {
						$partin->astatus=2;
					}
					
					if ($partin->save()) {
						Yii::app()->session['aid']=0;
						$this->redirect(array('success2', 'token'=>$_GET['token']));
					} else {
						$this->redirect(array('success', 'token'=>$_GET['token'], 'error_msg'=>'请重新输入手机号'));
					}
				}
			}
		}


		$this->render('success', array(
			'award'=>$award,
			'awardId'=>$awardId,
			'error_msg'=>$error_msg,
			'token'=>$_GET['token'],
		));
	}

	public function actionSuccess2() {
		if (!$uid=$this->initUser($_GET['token'])) {
			// 直接调用APP登录接口
			echo "<script>setTimeout(function(){top.postMessage('login','*');}, 1000);</script>";
			Yii::app()->end();
		}

		$this->render('success2', array(
			'token'=>$_GET['token'],
		));
	}

	public function actionRequestAward() {
		if (!$uid=$this->initUser($_GET['token'])) {
			// 直接调用APP登录接口
			echo "<script>setTimeout(function(){top.postMessage('login','*');}, 1000);</script>";
			Yii::app()->end();
		}

		$aid = $this->doLottery($uid);
		Yii::app()->session['aid']=$aid;

		echo $aid;
	}

	public function actionInstruction() {
		// 活动介绍
	}

	public function actionNotReady() {
		// 活动尚未开始
		$this->render('not_ready', array(
			'token'=>$_GET['token'],
			));
	}
	public function actionParticipated() {
		$this->render('participated', array(
			'token'=>$_GET['token'],
			));
	}

	private function doLottery($uid) {
		$randNum=mt_rand(1,$this->probabilityBase);
		$defaultAwardId=0;
		$defaultReturnId=0;

		$probabilityGroup=array();

		$cri=new CDbCriteria;
		$cri->select="id,probability";
		$cri->addCondition('probability > 0');
		$cri->addCondition('stock_num > 0');
		$cri->order="probability";
		$stockAll=ActivityStock::model()->findAll($cri);
		if (empty($stockAll)) {
			$awardId=$defaultAwardId;
			$returnId=$defaultReturnId;
			echo $returnId;
			Yii::app()->session['aid']=$awardId;
			return true;
		}
		foreach ($stockAll as $i=>$stock) {
			$stockId=$stock->id;
			$stockProbability=$stock->probability;
			$probabilityGroup[$stockProbability][]=$stockId;
		}

		$section_start=0;
		$section_end=0;
		$awardId=$defaultAwardId;
		ksort($probabilityGroup);
		foreach ($probabilityGroup as $probability => $aidArray) {
			$section_start=$section_end;
			$section_end+=$probability;
			//echo $section_start," < x <= ",$section_end,"<br>";
			if ($randNum > $section_start && $randNum <= $section_end) {
				// 落在某区间
				$awardRange=mt_rand(0,count($probabilityGroup[$probability])-1);
				$awardId=$probabilityGroup[$probability][$awardRange];
				//echo "$randNum => ($section_start, $section_end] => $probability => $awardId<br>";
				break;
			}
		}

//$awardId=1;

		$returnId=$awardId;

		//扣库存
		$ddd = ActivityStock::model()->findByPk($awardId);
		$ddd->stock_num--;
		$ddd->update();

		//$this->set_session($_GET['token'], 'aid', $awardId);
		//Yii::app()->session['aid']=$awardId;
		//echo $awardId;

		$in = new ActivitysepPartin;
		$in->session_id = $uid;
		$in->aid = $awardId;
		$in->ctime = time();
		$in->astatus = 1;
		$in->save();

		return $awardId;
	}

	public function set_session($token='', $k, $v)
	{
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
			Yii::app()->getSession()->setSessionID($token);
			Yii::app()->session[$k]=$v;
			return ture;
		}
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
