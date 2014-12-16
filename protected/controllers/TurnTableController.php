<?php

class TurnTableController extends Controller
{
  private $uid = 0;
	public function actionIndex()
	{
   //$this->uid=100001;
    //如果已经登陆，拉取数据
    //月份
    $month = date('Ym', time());
    //var_dump($month);
    
  

  

    $token = $this->getString('token');
    //user信息
    $user=User::model()->findByPk($this->uid);
    $usersign=userSign::model()->findByPk($this->uid);
    $coupon_text = Coupon::model()->findAll();
    $prop_text = Prop::model()->findAll();
    $sign_info = signInfo::model()->getSignInfoMonth($month);
    $cdate = date('Ymd', time());
    $ex=usersign::model()->find('uid=:uid and cdate=:cdate',array(':uid'=>$this->uid,':cdate'=>$cdate));

    if(!$usersign){
      $usersign=0;
    }
    if(!$ex){
      $days=0;
    }else{
      $days=$ex->sign_day;
    }
   // p($ex);die;
    $data=array(
      'token'=>$token,
      'user'=>$user,
      'usersign'=>$usersign,
      'sign_info'=>$sign_info,
      'days'=>$days,
    );
		$this->render('main', $data);
	}

   //ajax主方法
   public function actionMain(){
    $this->uid = $this->initUser($_GET['token']);
    //$this->uid=100001;
    if(!$this->uid){
      $data['err'] = 1;
       echo json_encode($data); 
       exit;
    }
     $this->checkUserScore();
    
     $random=$this->doLottery();
     $user=User::model()->findByPk($this->uid);
     $data=array(
             'award_id'=>$random,
             'user_score'=>$user->score,
            ); 
     //p($user->score);die;
    echo json_encode($data);
    

   }
   //检查用户积分
    private function checkUserScore()  
    {  
      if(!$this->uid) return;
      //如果已经登陆，查询用户的积分
      $user = User::model()->findByPk($this->uid);
      if(!$user) return;
      $ctime=strtotime("today midnight");
      $is_count=turntableuser::model()->find('uid=:uid and ctime >:ctime',array(':uid'=>$this->uid,':ctime'=>$ctime));
      if($is_count && $user->score < 10){      
           $data['err'] = 2;
           $this->renderJSON($data);
      }
     
    }
   

 //算法
  private function doLottery() {
    $this->uid=$this->initUser($_GET['token']);
    //$this->uid=100001;
    $defaultAwardId=0;
    $defaultReturnId=0;

    $probabilityGroup=array();

    $cri=new CDbCriteria;
    $cri->select="id,probability";
    $cri->addCondition('probability > 0');
    $cri->addCondition('stock_num > 0');
    $cri->addCondition('status > 0');
    $cri->order="probability";
    $stockAll=turntable::model()->findAll($cri);
    if (empty($stockAll)) {
      $awardId=$defaultAwardId;
      $returnId=$defaultReturnId;
      Yii::app()->session['aid']=$awardId;
      return $returnId;
    }
    $proBase = 0;
    foreach ($stockAll as $i=>$stock) {
      $proBase += $stock->probability;
      $stockId=$stock->id;
      $stockProbability=$stock->probability;
      $probabilityGroup[$stockProbability][]=$stockId;
    }

    $randNum=mt_rand(1,$proBase);
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
        $awardRange=mt_rand(0,count($aidArray)-1);
        $awardId=$aidArray[$awardRange];
        //echo "$randNum => ($section_start, $section_end] => $probability => $awardId<br>";
        break;
      }
    }

  //$awardId=1;
    if($awardId==0){
      $awardId=6;
    }
  

    $returnId=$awardId;

     // p($returnId);die;
    //扣库存
    $turn_table = turntable::model()->find('id=:id',array(':id'=>$returnId));
    $turn_table->stock_num--;
    $turn_table->update();
    $score=user::model()->findByPk($this->uid);
    $ctime=strtotime("today midnight");
    $is_count=turntableuser::model()->find('uid=:uid and ctime >:ctime',array(':uid'=>$this->uid,':ctime'=>$ctime));
  
    //扣积分
    if($is_count){
       $score->score-=10;
       $score->update(array('score'));
    }
    
    //增加积分
    if($turn_table->id<=7 && $turn_table->id>=4){
      $score=user::model()->findByPk($this->uid);
      $score->score+=$turn_table->award_name;
      $score->update(array('score'));
    }
    //增加拍券
    if($turn_table->id==3){
      $couponId = 1003;
      $coupon = Coupon::model()->findByPk($couponId);
      $uc = new UserCoupon;
      $uc->uid = $this->uid;
      $uc->coupon_id = $coupon->id;
      $uc->expire_time = $coupon->getExpireTime();
      $uc->num = 1;
      if(!$uc->insert()){
        return false;
        }   
    }
   //增加道具
    if($turn_table->id==2){
      $propId = 1;
      $prop = Prop::model()->findByPk($propId);
      $uc = new UserProp;
      $uc->uid = $this->uid;
      $uc->prop_id = $prop->id;
      $is=UserProp::model()->find('prop_id=:prop_id',array(':prop_id'=>$returnId));
      if(!$is){
         $uc->num = 1;
         if(!$uc->insert()){
            return false;
         }  
      }else{
         $is->num+=1;
         $is->update(array('num'));
      }
     
    }
     //双倍卡
    if($turn_table->id==1){
      $propId = 2;
      $prop = Prop::model()->findByPk($propId);
      $uc = new UserProp;
      $uc->uid = $this->uid;
      $uc->prop_id = $prop->id;
      $is=UserProp::model()->find('prop_id=:prop_id',array(':prop_id'=>$returnId));
      if(!$is){
         $uc->num = 1;
         if(!$uc->insert()){
            return false;
         }  
      }else{
         $is->num+=1;
         $is->update(array('num'));
      }
     
     
    }
  //奖品记录
    $turn_user=new turntableuser;
    $turn_user->uid=$this->uid;
    $turn_user->turn_table_id=$turn_table->id;
    $turn_user->ctime = time();
    if(!$turn_user->insert()){
       return false;
    }

    Yii::app()->session['aid']=$awardId;

   // echo $returnId;
    return $returnId;
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

//发放奖品记录
  public function recordCount(){

    $turn_user=turntableuser::model().findByPk($turn_table_id);

  }
}