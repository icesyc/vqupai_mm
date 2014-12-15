<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--领取3M口罩</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/act_shihui.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="http://m.vqupai.com/static/js/area.js"></script>
    <link rel="shortcut icon" href="vqupai.ico" />
</head>
<body>
<div class="contain">
  <section class="img_one">
    
  </section>
  <br><br>
  <h3 class="algin_center">请填写收货地址</h3>
    <section class="input_one algin_center">
        <form action="/mm/?r=actShihui/consignee&token=<?php echo $token;?>&verify_code=<?php echo $verify_code;?>&_dc=<?php echo time();?>" method="post">
            <input maxlength="12" name="consignee[verify_code]" id="consignee_verify_code" type="hidden" value="<?php echo $consignee['verify_code'];?>">
            姓名：<input style="width:200px;" maxlength="12" name="consignee[name]" id="consignee_name" type="text" value="<?php echo $consignee['name'];?>">
            <br>
            省份：<select style="width:200px;" id="consignee_province" name="consignee[province]"></select>
            <br>
            城市：<select style="width:200px;" id="consignee_city" name="consignee[city]"></select>
            <br>
            地址：<input style="width:200px;" maxlength="256" name="consignee[address]" id="consignee_address" type="text" value="<?php echo $consignee['address'];?>">
            <br>
            手机：<input style="width:200px;" maxlength="11" name="consignee[mobile]" id="consignee_mobile" type="text" value="<?php echo $consignee['mobile'];?>">
            <br>
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
<script type="text/javascript">
    AreaSelector.bind('consignee_province', 'consignee_city');
</script>