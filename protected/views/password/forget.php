<form method="post" action="">
<div class="form-body">

<div class="line">
<input class="input-field" type="text" id="user_identify" name="user_identity" placeholder="请填写注册用的手机号或邮箱" autocomplete="off" />
</div>

<div class="line">
<font color="red"><?php echo Yii::app()->user->getFlash('error'); ?></font>
</div>

<div class="line">
<input type="submit" class="btn btn-primary btn-mini" value="下一步" />
</div>

</div>
</form>
