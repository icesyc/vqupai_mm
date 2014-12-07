<?php
$this->pageTitle=Yii::app()->name;
?>

<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading'=>'欢迎浏览微趣拍'
)); ?>

<p>欢迎回来，<?php echo Yii::app()->user->name;?></p>
<?php $this->endWidget(); ?>



