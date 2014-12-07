<script>
document.domain='vqupai.com';
var app={};
app.redirect=function(view) {
	//top.location.hash=view;
	top.postMessage(view,'*');
}
</script>
<style>
div {
	vertical-align: middle;
	text-align: center;
}
</style>
<div class="form-body">
<div class="line">
	密码重置成功!
	<br />
</div>
<div class="line">
	<input type="button" class="btn" style="width: 30%" onclick="app.redirect('login');" value="登录" />
</div>
</div>
<script type="text/javascript">
//setTimeout("javascript:location.href='<?php echo Yii::app()->createUrl('password/index'); ?>'",2000);
</script>
