<div class="container">
  <input id="phone" class="input" type="text" placeholder="输入手机号，点击右侧" />
  <a href="#" class="btn" id="submit"></a>
</div>
<script type="text/javascript">
$("#submit").click(function(e){
  e.preventDefault();
  phone=$("#phone").val();
  url="/mm/index.php?r=actHlj/check&phone="+phone;

  $.get(url, function(rsp){
      alert(rsp.info);
      return;
  });
});
</script>