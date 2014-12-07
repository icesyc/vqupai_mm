<style>
.controls input {
	height: 30px;
}
</style>
<form class="form-horizontal" method="post" action="">
	<input type="hidden" name="Reset[uid]" id="resetId" value="<?php echo $uid; ?>" />
<div class="control-group">
  <label class="control-label" for="resetIdentity">用户名</label>
  <div class="controls">
	<input type="text" name="Reset[identity]" id="resetIdentity" value="<?php echo $userIdentity; ?>" readonly="readonly" />
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="resetPasswd1">输入新密码</label>
  <div class="controls">
	<input type="password" name="Reset[Passwd1]" id="resetPasswd1" placeholder="输入新密码" />
  </div>
</div>
<div class="control-group">
  <label class="control-label" for="resetPasswd1">再输入一遍</label>
  <div class="controls">
	<input type="password" name="Reset[Passwd2]" id="resetPasswd2" placeholder="再输入一遍新密码" />
  </div>
</div>
<div class="control-group">
  <div class="controls">
	<font color="red"><strong><?php echo Yii::app()->user->getFlash('error'); ?></strong></font>
  </div>
</div>
<div class="control-group">
  <div class="controls">
	<input type="submit" class="btn" value="确认修改" />
  </div>
</div>
</form>
