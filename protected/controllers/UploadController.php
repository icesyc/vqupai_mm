<?php
class UploadController extends CController {
	public function actionIndex() {
	}

	public function actionResume() {
		$file=CUploadedFile::getInstanceByName('fileField');
		$file_name=$file->name;
		$file_full_path="/alidata/www/vqupai/resume/".$file_name;
		$file->saveAs($file_full_path);
		header('Location: http://www.vqupai.com/');
		return true;
	}
}
?>
