<?php
class TempChangeStockCommand extends CConsoleCommand {
	public function actionStartChange() {
		$id100=array(9,13,14,15,16,17);
		$id449=array(28,29);
		$id451=array(19,20,22,23,24);
		
		$cri100=new CDbCriteria;
		$cri100->addInCondition('id', $id100);
		$stock100=WorldcupShareStock::model()->findAll($cri100);
		foreach ($stock100 as $i=>$stock) {
			print_r($stock->attributes());
		}

		$cri449=new CDbCriteria;
		$cri449->addInCondition('id', $id449);
		$stock449=WorldcupShareStock::model()->findAll($cri449);

		$cri451=new CDbCriteria;
		$cri451->addInCondition('id', $id451);
		$stock451=WorldcupShareStock::model()->findAll($cri451);
	}

	public function actionEndChange() {
		$id600=array(9,13,14,15,16,17);
		$id400=array(19);
	}
}
?>
