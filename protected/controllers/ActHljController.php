<?php
class ActHljController extends Controller {
	public $layout='//layouts/hlj';

	public function actionIndex()
	{
		if(isset($_GET['err_msg']))
			$err_msg = $_GET['err_msg'];
		else
			$err_msg = '';

		$this->render('index', array(
			'err_msg'=>$err_msg,
			));
	}

	public function actionCheck($phone)
	{
		if(empty($phone)){
			$data['success'] = false;
			$data['info'] = '请输入手机号！';
		}
		elseif (!is_numeric($phone) || strlen($phone)!=11){
			$data['success'] = false;
			$data['info'] = '请正确输入手机号！';
		}
		elseif (!$this->phoneValidator($phone)){
			$data['success'] = false;
			$data['info'] = '您输入的真的是手机号吗？';
		}
		else {
			$cri = new CDbCriteria;
			$cri->condition = sprintf('phone="%s"', $phone);
			$ck = ActHlj::model()->find($cri);
			if($ck){
				if($ck->times<4){
					//发送短信
					$content = '您的河狸家50元抵用券验证码是 '.$ck->code.' ';
					SMS::send($phone, $content);
					$ck->times++;
					$ck->update();
					$data['success'] = false;
					$data['info'] = '验证码已经再次发送给您，请妥善保存';
				}
				else{
					$data['success'] = false;
					$data['info'] = '此手机号已经被使用，请使用其它号码';
				}
			}
			else{
				$cri = new CDbCriteria;
				$cri->condition = sprintf('phone=""');
				$cri->order = 'id asc';
				$cri->limit = 1;
				$in = ActHlj::model()->find($cri);
				$in->phone = $phone;
				$in->times++;
				$in->ctime = time();
				$in->update();

				//发送短信
				$content = '您的河狸家50元抵用券验证码是 '.$in->code.' ';
				SMS::send($phone, $content);

				$data['success'] = true;
				$data['info'] = '您的验证码已经短信发送到您的手机，请妥善保存';
			}
		}
		$this->renderJSON($data);
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