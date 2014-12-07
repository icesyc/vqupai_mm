<?php

class AuctionController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/showAuction';


	public function actionShow($id = "") {

		if($id == ""){
			return false;
			exit;
		}

		$model = Auction::model()->findByPk($id);

		$this->render('show',array(
			'model'=>$model,
			));

	}
}
