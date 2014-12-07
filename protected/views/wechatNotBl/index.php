<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/act_shihui.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <link rel="shortcut icon" href="vqupai.ico" />
</head>
<body>
<div class="contain">
  <br><br>
    <section class="input_one algin_center">
        <h3 class="algin_center">请输入资料</h3>
        <form action="/mm/?r=wechatNotBl" method="post">
            <input class="input" name="open_id" id="open_id" type="text" maxlength="11" value="<?php echo Yii::app()->session['open_id'];?>" readOnly/>
            <br><br>
            <input class="input" name="name" id="name" type="text"  placeholder="请输入姓名" maxlength="11" />
            <br><br>
            <input class="input" name="code" id="code" type="text"  placeholder="请输入验证码" maxlength="11" />
            <br><br>
            <button class="btn" type="submit">确  定</button>
        </form>
    </section>
</div>
<div class="err_msg" style="font-size:18px;"><?php echo $err_msg;?></div>
<div>
    <br><br><br><br>
    <br><br><br>
</div>
</body>
</html>