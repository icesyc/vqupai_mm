<script type="text/javascript">
var app={};
app.redirect=function(view){
	top.location.hash=view;
}
</script>
<div class="form-body">
<div class="line">
	<input type="button" class="btn" onclick="app.redirect('login');" value="点此登录" />
</div>
<div class="line">
	<input type="button" class="btn" onclick="app.redirect('register');" value="点此注册" />
</div>
</div>
