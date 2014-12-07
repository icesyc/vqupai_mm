<div class="success">
    <section>&nbsp;</section>
    <section class="text">
      <div style="text-align: center;">
      	活动说明
      	<br />
      	注册/登录后，您就可以参加活动赢取心动大奖<br />
      	心动不如行动，快来参加吧！<br />
		<input id="into" type="submit" class="btn" value="我要参加">
	  </div>
    </section>
</div>
<script type="text/javascript">
$("#into").click(function(){
	top.postMessage('login','*');
});
</script>