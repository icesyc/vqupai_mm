<?php
/**
 * 商品详情对应的控制器
 * 如果有系统拍卖，会返回系统拍卖的信息
 */

class ShowItemController extends Controller
{
	public $layout = false;

	public function actionShow(){
		$this->actionIndex();
	}

	//商品详情
	public function actionIndex(){
		$data = array();
		if(!isset($_GET['token']))
			$_GET['token'] = '';
		$data['token'] = $_GET['token'];

		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if($id <= 0){
			return;
		}
		$criteria = new CDbCriteria;
		$criteria->select = 'id,title,oprice,pic_cover,pic_url,pic_top,description,shop_id,brand_id,model,specification,content';
		$item = Item::model()->findByPk($id, $criteria);
		if(!$item){
			echo 'item not exists';
			exit;
		}

		$item->pic_url = $item->getPicList();
		if(!$item->content){
			$item->content = $item->description;
		}
		if(!$item->pic_top){
			$item->pic_top = $item->pic_cover;
		}
		$data['item'] = $item->attributes;
		$data['auction'] = $this->getAuction($id);
		$this->render('/v2/show_item', $data);
	}

	public function actionOrder($auction_id)
	{
		$data = array();
		//如果未登陆，调用app登陆接口
		if (!$uid=$this->initUser($_GET['token'])) {
			echo "<script>setTimeout(function(){top.postMessage('login','*');}, 1000);</script>";
			Yii::app()->end();
		}
		$data['token'] = $_GET['token'];

		if(isset($_GET['err_msg']))
			$data['err_msg'] = $_GET['err_msg'];
		else
			$data['err_msg'] = '';

		$cri = new CDbCriteria;
		$cri->condition = sprintf('t.id=%d', $auction_id);
		$cri->with = 'item';
		$auction = Auction::model()->find($cri);
		$data['auction'] = $auction;
		$data['item'] = $auction->item;

		//获取收货地址
		$cri = new CDbCriteria;
		$cri->condition = sprintf('uid=%d and is_default=1', $uid);
		$consignee = UserConsignee::model()->find($cri);
		if($consignee)
			$data['consignee'] = $consignee;
		
		$this->render('/v2/order',$data);
	}

	public function actionOrderCheck()
	{
		$data = array();
		//如果未登陆，调用app登陆接口
		if (!$uid=$this->initUser($_GET['token'])) {
			echo "<script>setTimeout(function(){top.postMessage('login','*');}, 1000);</script>";
			Yii::app()->end();
		}
		$data['token'] = $_GET['token'];
		$err_msg = '';

		if(isset($_POST['consignee'])) {
			$consignee = $_POST['consignee'];
			$consignee['uid'] = $uid;
			$auction_id = $consignee['auction_id'];
			$item_id = $consignee['item_id'];
			$cri_c = new CDbCriteria;
			$cri_c->condition = sprintf('t.id=%d and t.item_id=%d', $auction_id, $item_id);
			$ck = Auction::model()->find($cri_c);
			if(!$ck){
				$this->redirect(array('index','id'=>$item_id));
				Yii::app()->end();
			}

			//var_dump($_POST);
			if(empty($consignee['name'])) {
				$err_msg = '请填写收货人姓名！';
				$this->redirect(array('order','auction_id'=>$auction_id, 'err_msg'=>$err_msg));
				Yii::app()->end();
			}
			elseif (empty($consignee['province'])) {
				$err_msg = '请选择省份！';
				$this->redirect(array('order','auction_id'=>$auction_id, 'err_msg'=>$err_msg));
				Yii::app()->end();
			}
			elseif (empty($consignee['city'])) {
				$err_msg = '请选择城市！';
				$this->redirect(array('order','auction_id'=>$auction_id, 'err_msg'=>$err_msg));
				Yii::app()->end();
			}
			elseif (empty($consignee['address'])) {
				$err_msg = '请填写详细地址！';
				$this->redirect(array('order','auction_id'=>$auction_id, 'err_msg'=>$err_msg));
				Yii::app()->end();
			}
			elseif (empty($consignee['mobile'])) {
				$err_msg = '请填写手机号！';
				$this->redirect(array('order','auction_id'=>$auction_id, 'err_msg'=>$err_msg));
				Yii::app()->end();
			}
			elseif (!is_numeric($consignee['mobile']) || strlen($consignee['mobile'])!=11 ) {
				$err_msg = '手机号必须为11位数字！';
				$this->redirect(array('order','auction_id'=>$auction_id, 'err_msg'=>$err_msg));
				Yii::app()->end();
			}
			elseif (!$this->phoneValidator($consignee['mobile'])) {
				$err_msg = '请正确填写手机号！';
				$this->redirect(array('order','auction_id'=>$auction_id, 'err_msg'=>$err_msg));
				Yii::app()->end();
			}
			else {
				//写order表，写user_consignee表，渲染订单显示页面
				$cri = new CDbCriteria;
				$cri->condition = sprintf('uid=%d and name="%s" and province="%s" and city="%s" and address="%s" and mobile="%s"', $uid,$consignee['name'],$consignee['province'],$consignee['city'],$consignee['address'],$consignee['mobile']);
				$ck = UserConsignee::model()->find($cri);
				if(!$ck){
					$uc = new UserConsignee;
					$uc->uid = $uid;
					$uc->name = $consignee['name'];
					$uc->province = $consignee['province'];
					$uc->city = $consignee['city'];
					$uc->address = $consignee['address'];
					$uc->mobile = $consignee['mobile'];
					$uc->insert();
					$consignee['id'] = $uc->id;
				}
				$consignee['id'] = $ck->id;

				$item = Item::model()->findByPk($item_id);
				$purchase = Purchase::model()->findByPk($item_id);
				$auction = Auction::model()->findByPk($auction_id);

				$order = new Order;
				$order->auction_id = $auction_id;
				$order->shop_id = $item->shop_id;
				$order->item_id = $item->id;
				$order->uid = $uid;
				$order->num = 1;
				$order->price = $auction->curr_price;
				if($auction->discount==0)
					$order->total_pay = $auction->curr_price;
				else
					$order->total_pay = $auction->curr_price - $auction->discount;
				$order->delivery_time = $consignee['delivery_time'];
				$order->ctime = time();
				$order->status = Order::STATUS_TOPAY;
				$order->comment = '付邮领用';
				$order->third_ship = $item->third_ship;
				$order->auction_type = 1;
				$order->coupon_info = '""';
				$order->purchase_price = $purchase->price;
				$order->can_use_coupon = 0;
				$order->name = $consignee['name'];
				$order->province = $consignee['province'];
				$order->city = $consignee['city'];
				$order->address = $consignee['address'];
				$order->mobile = $consignee['mobile'];
				$order->consignee_info = json_encode($consignee);
				$order->insert();

				//扣减库存
				$auction->left_num--;
				$auction->update();

				$data['order'] = $order;
				$data['item'] = $item;
				$data['auction'] = $auction;
				$data['err_msg'] = $err_msg;

				$this->render('/v2/order_show', $data);
			}
		}
		else {
			$this->redirect(array('index', 
						'id'=>$_GET['item_id'],
						));
		}
	}


	//获取该商品系统的自动拍卖
	private function getAuction($itemId){
		$criteria = new CDbCriteria;
		$criteria->select = 'id,item_id,curr_price,status,round_start_time,start_time,time_interval,curr_round,discount,sale_id';
		$criteria->condition = sprintf('item_id=%d and status = %d', $itemId, Auction::STATUS_ONLINE);
		$auction = Auction::model()->find($criteria);
		if(!$auction){
			return null;
		}
		return $auction->attributes;
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
