<?php
class Upload extends CActiveRecord {
	public $file;
	
	public function rules() {
		return array(
			array('file', 'file', 'types'=>'doc, rar, pdf, zip'),
		);
	}
}
?>
