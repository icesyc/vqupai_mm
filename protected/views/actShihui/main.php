<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--领取3M口罩</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/act_shihui.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <link rel="shortcut icon" href="vqupai.ico" />
</head>
<body>
<div class="contain">
  <section class="img_one">
    
  </section>
  <br><br>
    <section class="input_one algin_center">
        <h3 class="algin_center">请填写活动验证码</h3>
        <form action="/mm/?r=actShihui/main&token=<?php echo $token;?>&_dc=<?php echo time();?>" method="post">
            <input class="input" name="verify_code" id="verify_code" type="text"  placeholder="请输入活动验证码" maxlength="11" />
            <br><br>
            <button class="btn" type="submit">确  定</button>
        </form>
    </section>
</div>
<div class="err_msg" style="font-size:18px;"><?php echo $err_msg;?></div>
<div>
    <br><br><br><br><br><br><br>
</div>
</body>
</html>