<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $item['title'];?></title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="http://m.vqupai.com/static/js/area.js"></script>
    <script>
	 $(function(){
		      $(".container").css("height",$(window).height();)	 
			 })
	</script>
	<style type="text/css">
	html,body{height:100%;overflow:hidden;}
	footer{position:absolute;bottom:-1px;height:35px;width:100%;}
.downbtn{width:100%;position: relative;background:#000000;opacity:0.5;filter:alpha(opacity=50);height:35px;z-index:99;text-align: center;}
.btn_bt{position: absolutee;margin:auto;left:0; right:0;  bottom:0px;top:0px;opacity:0.8;filter:alpha(opacity=70);background: #ff2a61;width:94px;height:27px;border:1px solid #bfbfbf;text-decoration: none;color:white;margin:auto;border-radius: 4px; text-align:center;z-index:999999;}
.btn_bt_a{position: absolute;margin:auto;left:0; right:0;  bottom:0px;top:0px;width:97px; height:15px;font-size: 14px;color:white;text-decoration: none;text-align: center;z-index:99999999;}
.container{overflow:auto;-webkit-overflow-scrolling:touch;}
.btn_bt_2{position: fixed;margin:auto;left:50; right:0;  bottom:3px;opacity:0.8;filter:alpha(opacity=70);background: #ff2a61;width:94px;height:27px;border:1px solid #bfbfbf;text-decoration: none;color:white;margin:auto;border-radius: 4px; text-align:center;z-index:999999;}
.btn_bt_3{position: fixed;margin:auto;left:0; right:50;  bottom:3px;opacity:0.8;filter:alpha(opacity=70);background: #ff2a61;width:94px;height:27px;border:1px solid #bfbfbf;text-decoration: none;color:white;margin:auto;border-radius: 4px; text-align:center;z-index:999999;}
.btn_bt_b{position: fixed;margin:auto;left:50; right:0;  bottom:10px;width:97px; height:15px;font-size: 14px;color:white;text-decoration: none;text-align: center;z-index:99999999;}
.btn_bt_c{position: fixed;margin:auto;left:0; right:50;  bottom:10px;width:97px; height:15px;font-size: 14px;color:white;text-decoration: none;text-align: center;z-index:99999999;}
.item_area{width: 95%;background-color: #f5f5f5; padding: 5px; margin: 3px;}
.item_img{padding: 1px; display: inline-block; margin-right: 5px; vertical-align:middle;}
.item_title{display: inline-block; width: 220px;}
.price_text{width: 95%; text-align: right;}
.consignee_submit{position: relative;margin:auto;bottom:3px;opacity:0.8;filter:alpha(opacity=70);background: #ff2a61;width:94px;height:27px;border:1px solid #bfbfbf;text-decoration: none;color:white;margin:auto;border-radius: 4px; text-align:center;z-index:999999;}
input{padding: 3px 3px;
width: 97%;
line-height: 24px;
border-radius: 4px;
border: 1px solid #ccc;
margin-bottom: 8px;}
select{border: 1px solid #ccc;
font-size: 12px;
position: relative;
zoom: 1;
margin: -1px;
width: 100%;
height: 30px;
margin-bottom: 8px;}
    </style>
</head>
<body>
<div class="container wei">
    <div class="w_320 mg_auto">
      <div class="item_area">
        <img class="item_img" src="<?php echo $item['pic_top'];?>" width="50" height="50">
        <div class="item_title"><?php echo $item['title'];?></div>
      </div>
      <div class="price_text">
        <br>订单号码：<span class="wechatcolor"><?php echo $order['id'];?></span>
         订单金额：￥<span class="wechatcolor"><?php echo $order['total_pay'];?></span>
      </div>
      <div class="price_text">
        <br>
        <a class="consignee_submit" href="http://m.vqupai.com/?r=appv2/pay&id=<?php echo $order->id;?>&payment=alipay&token=<?php echo $token;?>">支付宝支付</a>
        <a class="consignee_submit" href="http://m.vqupai.com/?r=appv2/pay&id=<?php echo $order->id;?>&payment=umpay&token=<?php echo $token;?>">网银支付</a>
      </div>
  <h3 style="padding-left:10px;">收货信息</h3>
  <h3 style="padding-left:10px;color:red;"><?php echo $err_msg;?></h3>
    <section class="input_one algin_center" style="padding:5px; margin:10px;">
          <p><strong>收货人：</strong> <?php echo $order->name;?></p>
          <p><strong>地址：</strong> <?php echo $order->province.$order->city.$order->address;?></p>
          <p><strong>联系电话：</strong> <?php echo $order->mobile;?></p>
    </section>
    </div>
</div>
<footer>
 <div class="downbtn"></div>
 <div class="btn_bt_2"></div><div class="btn_bt_3"></div>
 <a id="consignee_submit_zfb" class="btn_bt_b" href="http://m.vqupai.com/?r=appv2/pay&id=<?php echo $order->id;?>&payment=alipay&token=<?php echo $token;?>">支付宝支付</a><a id="consignee_submit_wy" class="btn_bt_c" href="http://m.vqupai.com/?r=appv2/pay&id=<?php echo $order->id;?>&payment=umpay&token=<?php echo $token;?>">网银支付</a>
</footer>
 </body>
</html>
