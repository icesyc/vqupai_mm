<?php

class PasswordController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/password';

	public function actionIndex($text='') {
		$this->render('index',array(
			'text'=>$text,
		));
	}

	public function actionReset() {
		$this->redirect(array('forget'));
	}

	public function actionForget() {
		if (!empty($_POST['user_identity'])) {
			$userIdentity=$_POST['user_identity'];
			$type="";
			if (!is_numeric($userIdentity) || strlen($userIdentity)!=11) { 
				// 不是手机号
				if (!preg_match("/^([\w-_]+)@(([\w-_]+)\.)+([a-zA-Z]{2,3})$/", $userIdentity)) {
				// 也不是邮箱
					Yii::app()->user->setFlash('error', '请输入正确的手机号或邮箱');
					$this->render('forget');
					Yii::app()->end();
				} else {
					$type="mail";
				}
			} else {
				$type="mobile";
			}
			// 验证用户是否存在
			$user=User::model()->findByAttributes(array('uname'=>$userIdentity));
			if (!$user) {
				if ($type=="mail") {
					$user=User::model()->findByAttributes(array('email'=>$userIdentity));
				} elseif ($type=="mobile") {
					$user=User::model()->findByAttributes(array('phone'=>$userIdentity));
				}
				if (!$user) {
					Yii::app()->user->setFlash('error', '用户不存在，请重新输入');
					$this->render('forget');
					Yii::app()->end();
				}
			}
			$this->render('send_verification',array(
				'uid'=>$user->id,
				'type'=>$type,
				'userIdentity'=>$userIdentity,
			));
			Yii::app()->end();
		}
		$this->render('forget');
	}

	public function actionResetPasswd($id,$token) {
		$now=time();
		$user=User::model()->findByPk($id);
		if (!$user) {
			Yii::app()->user->setFlash('error', "非法请求/USER_INVALID");
			$this->render('error_page');
			Yii::app()->end();
		}
		/* TO-DO identity可能为email、uname、phone中的某一个 */
		$uv=UserVerification::model()->findByAttributes(array('uid'=>$id, 'token'=>$token));
		if (!$uv) {
			Yii::app()->user->setFlash('error',"非法请求/TOKEN_INVALID");
			$this->render('error_page');
			Yii::app()->end();
		}
		if ($now > $uv->expireTime) {
			Yii::app()->user->setFlash('error',"非法请求/EXPIRED");
			$this->render('error_page');
			Yii::app()->end();
		}
		$viewFile="";
		if ($uv->type=="mobile") {
			$viewFile="reset_passwd";
		} elseif ($uv->type=="mail") {
			$this->layout='//layouts/password_mail';
			$viewFile="reset_passwd_mail";
		}

		if (isset($_POST['Reset'])) {
			$identity=$_POST['Reset']['identity'];
			$p1=$_POST['Reset']['Passwd1'];
			$p2=$_POST['Reset']['Passwd2'];
			// 其他验证如长度、字符等 TO-DO
			if ($p1!=$p2) {
				Yii::app()->user->setFlash('error', "两次输入密码不一致，请重新输入");
				$this->render($viewFile,array('uid'=>$id,'userIdentity'=>$identity));
				Yii::app()->end();
			}
			/* 其他密码要求 */
			if (!preg_match('/^[\S]{6,}$/', $p1)) {
				Yii::app()->user->setFlash('error', "密码至少为6位");
				$this->render($viewFile,array('uid'=>$id,'userIdentity'=>$identity));
				Yii::app()->end();
			}
			$user=User::model()->findByPk($id);
			// 重置密码
			$user->passwd=md5($p1.$user->salt);
			if ($user->save()) {
				if ($uv->type=="mobile") {
					$this->render('reset_success');
				} elseif ($uv->type=="mail") {
					$this->render('reset_success_mail');
				}
				Yii::app()->end();
			} else {
				Yii::app()->user->setFlash('error', "系统错误(001)，请重试");
				$this->render($viewFile, array('uid'=>$id, 'userIdentity'=>$identity));
				Yii::app()->end();
			}
		} else {
			$this->render($viewFile,array(
				'uid'=>$id,
				'userIdentity'=>$user->uname,
			));
			Yii::app()->end();
		}
	}

	public function actionTest() {
		$this->render('reset_success_mail');
	}

	public function actionVerification() {
		// 提交验证表单
		if (isset($_POST['User'])) {
			$uid=$_POST['User']['uid'];
			$type=$_POST['User']['type'];
			$identity=$_POST['User']['identity'];
			$verifyString=$_POST['User']['verifyString'];
			$criteria=new CDbCriteria;
			$criteria->compare('uid', $uid);
			$criteria->compare('identity', $identity);
			$criteria->compare('verifyString', $verifyString);
			$criteria->order="expireTime DESC";
			$criteria->limit=1;
			if ($model=UserVerification::model()->find($criteria)) {
				$now=time();
				if ($now >= $model->expireTime) {
					Yii::app()->user->setFlash('error', '验证码已过期，请重新点击发送验证码');
					$this->render('send_verification',array(
						'uid'=>$uid,
						'type'=>$type,
						'userIdentity'=>$identity,
					));
					Yii::app()->end();
				}
				// 验证成功，跳转到重设密码页面
				$token=$model->token;
				$this->redirect(array("resetPasswd",'id'=>$uid,'token'=>$token));
			} else {
				// 没有验证码记录
				Yii::app()->user->setFlash('error', "验证码错误，请重新确认");
				$this->render('send_verification',array(
					'uid'=>$uid,
					'type'=>$type,
					'userIdentity'=>$identity,
				));
				Yii::app()->end();
			}
		}
	}

	public function actionSendMobile($id,$mobile) {
		// 发送手机验证码
		$expireDuration="600"; // 单位 秒
		// 算出6位数验证码，存入DB，设置过期时间
		$verifyCode=$this->genVerifyCode();
		if (empty($verifyCode)) {
			echo '{"code":"501","status":"fail","desc":"系统繁忙，请重试"}';
			return false;
		}
		$expireTime=time()+$expireDuration;

		$user=User::model()->findByPk($id);
		$model=new UserVerification;
		$model->uid=$id;
		$model->type="mobile";
		$model->identity=$mobile;
		$model->verifyString=$verifyCode;
		$model->token=md5($verifyCode.$user->salt);
		$model->expireTime=$expireTime;
		$model->sended=1;
		if (!$model->save()) {
			echo '{"code":"502","status":"fail","desc":"系统错误，请重试"}';
			return false;
		}

		$sendContent="您申请重置密码的验证码为：".$verifyCode."。有效期";
		$sendContent.=intval($expireDuration/60);
		$sendContent.="分钟，请及时使用。【微趣拍】";
		if (SMS::send($mobile, $sendContent)) {
			echo '{"code":"200","status":"success","desc":"验证码已发送到您的手机"}';
			return true;
		} else {
			echo '{"code":"500","status":"fail","desc":"验证码发送错误，请重试"}';
			return false;
		}
	}

	public function actionSendMail() {
		if (isset($_POST['User'])) {
			$mail=$_POST['User']['identity'];
			$uid=$_POST['User']['uid'];
			$type=$_POST['User']['type'];
			$verifyCode=$this->genVerifyCode();
			if (empty($verifyCode)) {
				Yii::app()->user->setFlash('error', "系统错误(01)，请重试");
				$this->render('send_verification',array(
					'uid'=>$uid,
					'type'=>$type,
					'userIdentity'=>$mail,
				));
				Yii::app()->end();
			}
			$expireDuration=600;
			$expireTime=time()+$expireDuration;

			$user=User::model()->findByPk($uid);
			$model=new UserVerification;
			$model->uid=$uid;
			$model->type="mail";
			$model->identity=$mail;
			$model->verifyString=$verifyCode;
			$model->token=md5($verifyCode.$user->salt);
			$model->expireTime=$expireTime;
			$model->sended=1;
			$token=md5($verifyCode.$user->salt);
			if (!$model->save()) {
				Yii::app()->user->setFlash('error', "系统错误(02)，请重试");
				$this->render('send_verification',array(
					'uid'=>$uid,
					'type'=>$type,
					'userIdentity'=>$mail,
				));
				Yii::app()->end();
			}
			$subject="重置您的密码【微趣拍】";
			$content="亲爱的".$user->uname."，<br>请点击以下链接重置密码：<br />";
			$content.='<a href="http://www.vqupai.com';
			$content.=Yii::app()->createurl('password/resetPasswd',array('id'=>$user->id, 'token'=>$token));
			$content.='">点此重置密码</a><br>';
			$content.="或者复制以下链接<br />";
			$content.="http://www.vqupai.com";
			$content.=Yii::app()->createUrl('password/resetPasswd',array('id'=>$user->id, 'token'=>$token));
			$content.="<br>您会收到此邮件，是由于您在【微趣拍】发起了重置登录密码的操作。如果不是您本人发起的操作，请立即&nbsp;<a href='mailto:huangzhuoman@vqupai.com'>联系【微趣拍】</a>";
			if ($this->sendMail($mail, $subject, $content)) {
				$this->render('send_mail_success',array(
					'mail'=>$mail,
				));
				Yii::app()->end();
			} else {
				Yii::app()->user->setFlash('error', "系统错误(03)，请重试");
				$this->render('send_verification',array(
					'uid'=>$uid,
					'type'=>$type,
					'userIdentity'=>$mail,
				));
				Yii::app()->end();
			}
		}
	}

	private function sendMail($mail, $subject, $content) {
		$mailer=Yii::createComponent('application.extensions.mailer.EMailer');
		$mailer->Host="smtp.exmail.qq.com";
		$mailer->IsSMTP();
		$mailer->IsHTML();
		$mailer->SMTPAuth=true;
		$mailer->From='pw@vqupai.com';
		$mailer->FromName='微趣拍';
		$mailer->AddReplyTo('pw@vqupai.com');
		$mailer->AddAddress($mail);
		$mailer->Username='pw@vqupai.com';
		$mailer->Password='We1qupai';
		$mailer->SMTPDebug=false;
		$mailer->CharSet='UTF-8';
		$mailer->Subject=$subject;
		$mailer->Body=$content;
		if ($mailer->send()) {
			return true;
		} else {
			return false;
		}
	}

	private function genVerifyCode() {
		$code=mt_rand(100000,999999);
		return $code;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='activity-lottery-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
