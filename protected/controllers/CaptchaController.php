<?php
class CaptchaController extends CController{

	public function actionIndex(){
		$captcha = new Captcha;
		$captcha->image();
	}	
}