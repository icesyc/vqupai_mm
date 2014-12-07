<script>
function sendVerify() {
	$(".reminder").text('');
	var uid=document.getElementById("userId").value;
	var mobile=document.getElementById("userIdentity").value;
	var url="<?php echo Yii::app()->createUrl('password/sendMobile', array('id'=>$uid, 'mobile'=>$userIdentity)); ?>";
	$.get(url, function(data, status, datatype){
		if (status=="success") {
			console.log(data);
			var ret=JSON.parse(data);
			$("#sendRemind").text(ret.desc);
		}
	});
}
</script>


<?php if ($type=="mobile") { ?>

<form method="post" action="<?php echo Yii::app()->createUrl('password/verification'); ?>">
<div class="form-body">
	<input type="hidden" name="User[uid]" id="userId" value="<?php echo $uid; ?>" />
	<input type="hidden" name="User[type]" id="userType" value="<?php echo $type; ?>" />
<div class="line">
	<input type="text" class="input-field" name="User[identity]" id="userIdentity" value="<?php echo $userIdentity; ?>" readonly="readonly" />
</div>
<div class="line">
	<input type="button" class="btn btn-mini btn-primary" id="sendVerifyCode" onclick="sendVerify();" value="获取验证码" />
	<br />
	<font color="red" size="-1" id="sendRemind" class="reminder"></font>
</div>
<div class="line">
	<input type="text" class="input-field" name="User[verifyString]" id="verifyCode" placeholder="请输入您接收到的验证码" />
</div>
<div class="line">
	<font color="red" class="reminder"><?php echo Yii::app()->user->getFlash('error'); ?></font>
</div>
<div class="line">
	<input type="submit" class="btn" value="下一步" />
</div>
<div class="line">
	<input type="button" class="btn" value="上一步" onclick="window.location.href='<?php echo Yii::app()->createUrl('password/reset'); ?>'">
</div>
</div>
</form>

<?php } elseif ($type="mail") { ?>

<form method="post" action="<?php echo Yii::app()->createUrl('password/sendMail'); ?>">
<div class="form-body">
	<input type="hidden" name="User[uid]" id="userId" value="<?php echo $uid; ?>" />
	<input type="hidden" name="User[type]" id="userType" value="<?php echo $type; ?>" />
<div class="line">
	<input type="text" class="input-field" name="User[identity]" id="userIdentity" value="<?php echo $userIdentity; ?>" readonly="readonly">
</div>
<div class="line">
	<font color="red" class="reminder"><?php echo Yii::app()->user->getFlash('error'); ?></font>
</div>
<div class="line">
	<input type="submit" class="btn" value="发送验证邮件" />
</div>
<div class="line">
	<input type="button" class="btn" value="上一步" onclick="window.location.href='<?php echo Yii::app()->createUrl('password/forget'); ?>'">
</div>
</div>
</form>

<?php } ?>
