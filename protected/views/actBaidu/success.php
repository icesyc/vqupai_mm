<div class="success">
    <section>&nbsp;</section>
    <section class="text">
      <div style="text-align: center;">
		恭喜您抽中了<br/>
		市场价值<span id="price"><?php echo $award->mprice ?></span>元的<span id="name"><?php echo $award->name; ?></span>一<?php echo $award->unit; ?>!
		<br/>
		<img src="<?php echo $this->_imagesUri.$award->pic_uri; ?>" />
		<br />
		<font color="red">请留下您的手机号，以便我们联系您发放奖品 ^_^</font><br ?>
		<form enctype="multipart/form-data" id="submit_phone" action="http://www.vqupai.com/mm/index.php?r=actBaidu/success&token=<?php echo $token;?>" method="post">
			<input id="phone" name="phone" value="">
			<br />
			<font color="red"><?php if($error_msg!='') echo ''.$error_msg.'<br />';?></font>
			<input type="submit" class="btn" value="确认">
		</form>
	  </div>
    </section>
</div>
<script type="text/javascript">
$('#phone').on('focus', function(){
		document.body.style.webkitTransform = 'translate(0, -30%)';
});
$('#phone').on('focusout', function(){
		document.body.style.webkitTransform = 'translate(0, 0)';
});
</script>
