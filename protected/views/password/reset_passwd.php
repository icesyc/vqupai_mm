<form method="post" action="">
<div class="form-body">
	<input type="hidden" name="Reset[uid]" id="resetId" value="<?php echo $uid; ?>" />
<div class="line">
	<input type="text" class="input-field" name="Reset[identity]" id="resetIdentity" value="<?php echo $userIdentity; ?>" readonly="readonly" />
</div>
<div class="line">
	<input type="password" class="input-field" name="Reset[Passwd1]" id="resetPasswd1" placeholder="输入新密码" />
</div>
<div class="line">
	<input type="password" class="input-field" name="Reset[Passwd2]" id="resetPasswd2" placeholder="再输入一遍新密码" />
</div>
<div class="line">
	<font color="red"><?php echo Yii::app()->user->getFlash('error'); ?></font>
</div>
<div class="line">
	<input type="submit" class="btn btn-primary" value="确认修改" />
</div>

</div>
</form>
