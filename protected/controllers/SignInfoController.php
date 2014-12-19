<?php

class SignInfoController extends Controller
{
	private $uid = 0;
	private $err = 0;
	public function actionIndex()
	{  

		$this->render('/turntable/sign');
	}
  public function actionMain(){
  	  $token = $this->getString('token');
      $this->uid = $this->initUser($token);
	  // $this->uid=100001;
	    //未登录返回
	    if(!$this->uid){
	      $this->err = 1;
	       //echo json_encode($data); 
	       exit;
	    }
	     //控制一天只能签到一次
	     $month = date('Ym', time()); 
	     $cdate = date('Ymd', time());
	     $is_count=UserSign::model()->find('uid=:uid and month=:month and cdate=:cdate',array(':uid'=>$this->uid,'month'=>$month,':cdate'=>$cdate));
	     if($is_count){      
	           $this->err=2;
	     }
	     
         if($this->err==2){
        
         }else{
           //具体操作
           $this->addEdit();
         }
        //p($is_count->sign_day);die;

	   $user=User::model()->findByPk($this->uid);
       $days=UserSign::model()->find('uid=:uid and cdate=:cdate',array(':uid'=>$this->uid,':cdate'=>$cdate));
       //p($days['sign_day']);die;
	     $data=array(
             'user_score'=>$user->score,
             'user_exp'=>$user->exp,
             'err'=>$this->err,
             'days'=>$days['sign_day'],
            ); 
	         
 

    echo json_encode($data);
		

  }
  public function addEdit(){
    	$cdate = date('Ymd', time());
	    $month = date('Ym', time());
	    $sign_info = SignInfo::model()->getSignInfoMonth($month);
	    $day=Yii::app()->request->getParam('day');
	   //查询当月当天的奖品
	    $list=SignInfo::model()->find('month=:month and day=:day',array(':month'=>$month,':day'=>$day));
        $user=User::model()->findByPk($this->uid);
        //P($list);die;
	    //积分操作
	    //p($list->score);die;
	    if($list->score!=0){
	    	  $user=User::model()->findByPk($this->uid);
		      $user->score+=$list->score;
		      $user->update(array('score'));
	    }
        //经验值操作
        if($list->exp!=0){
	    	  $user=User::model()->findByPk($this->uid);
		      $user->exp+=$list->exp;
		      $user->update(array('exp'));
	    }
	    //增加拍券操作
	    if($list->coupon==1003){
	    	  // $couponId = 1003;
		      // $coupon = Coupon::model()->findByPk($couponId);
		      // $uc = new UserCoupon;
		      // $uc->uid = $this->uid;
		      // $uc->coupon_id = $coupon->id;
		      // $uc->expire_time = $coupon->getExpireTime();
		      
		      // $is=UserCoupon::model()->find('coupon_id=:coupon_id and uid=:uid',array(':coupon_id'=>$couponId,'uid'=>$this->uid));
		      // if(!$is){
		      //   $uc->num = 1;
		      //    if(!$uc->insert()){
		      //       return false;
		      //    }  
		      // }else{
		      //    $is->num+=1;
		      //    $is->update(array('num'));
		      // }
	    	   $couponId = 1003;
		       $coupon = Coupon::model()->findByPk($couponId);
		       $uc = new UserCoupon;
		       $uc->uid = $this->uid;
		       $uc->coupon_id = $coupon->id;
		       $uc->expire_time = $coupon->getExpireTime();
		       $uc->num = 1;
		      if($uc->save()>0){  
		    
		       }else{  
		          echo "添加失败";  
		       }     
            // p($is);die;
	    }
	    //增加道具操作
	    if($list->prop==1){
	      $propId = 1;
	      $prop = Prop::model()->findByPk($propId);
	      $uc = new UserProp;
	      $uc->uid = $this->uid;
	      $uc->prop_id = $prop->id;
	      $is=UserProp::model()->find('prop_id=:prop_id and uid=:uid',array(':prop_id'=>$propId,'uid'=>$this->uid));
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
	     //增加双倍卡操作
	    if($list->prop==2){
	      $propId = 2;
	      $prop = Prop::model()->findByPk($propId);
	      $uc = new UserProp;
	      $uc->uid = $this->uid;
	      $uc->prop_id = $prop->id;
	      $is=UserProp::model()->find('prop_id=:prop_id and uid=:uid',array(':prop_id'=>$propId,'uid'=>$this->uid));
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
	     //签到记录
	     
	     $list_sign=UserSign::model()->find('uid=:uid and month=:month',array('uid'=>$this->uid,':month'=>$month));
	      if(!$list_sign){
	         $user_sign=new UserSign;
			 $user_sign->uid=$this->uid;		 
			 $user_sign->month=$month;
		     $user_sign->cdate = $cdate;
	         $user_sign->sign_day=1; 
		     if($user_sign->save()>0){  
			   
			 }else{  
			    echo "添加失败";  
			 }  
	        
	         $model=UserSign::model()->findAll();
	        // p($model);die;
	      }else{
	           $list_sign->sign_day+=1;
	           $list_sign->cdate = $cdate;
	           $list_sign->update(array('sign_day'));
	           $list_sign->update(array('cdate'));
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
}