<?php
class FeedbackController extends Controller {
	public function actionIndex() {
		// 官网：帮助和反馈
		if (isset($_POST)) {
			$model=new HomepageFeedback;
			$model->attributes=$_POST;
			$model->ctime=time();
			if (empty($model->name)) {
				$ret=array('code'=>500, 'content'=>'请填入您的姓名。');
				echo json_encode($ret);
				Yii::app()->end();
			} elseif (empty($model->email) && empty($model->phone)) {
				$ret=array('code'=>500, 'content'=>'请输入email或者phone，以便我们及时与您联系。');
				echo json_encode($ret);
				Yii::app()->end();
			} elseif (empty($model->content)) {
				$ret=array('code'=>500, 'content'=>'请留下您的建议和反馈。微趣拍感谢您对我们的关注和支持。');
				echo json_encode($ret);
				Yii::app()->end();
			}
			if (!empty($model->email) && !preg_match('/(\w)+@(\w+\.)+(\w+)$/',$model->email)) {
				$ret=array('code'=>501, 'content'=>'邮箱格式错误，请重新输入。');
				echo json_encode($ret);
				Yii::app()->end();
			}
			if ($model->insert()) {
				$ret=array('code'=>200, 'content'=>'success');
				echo json_encode($ret);
				Yii::app()->end();
			} else {
				$ret=array('code'=>502, 'content'=>'系统繁忙，请稍后重新提交。');
				echo json_encode($ret);
				Yii::app()->end();
			}
		} else {
			throw new CHttpException(400, 'illegal request.');
		} 
	}
}
?>
