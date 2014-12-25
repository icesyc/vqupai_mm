
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
			
			$('.container').css("height",$(window).height());
			})
   </script>
	<style type="text/css">
html,body{height:100%;overflow:hidden;}
footer{position:absolute;bottom:-1px;height:35px;width:100%;}	
.downbtn{width:100%;position: relative;background:#000000;opacity:0.5;filter:alpha(opacity=50);height:35px;z-index:99;text-align: center;}
.btn_bt{position: absolute;;margin:auto;left:0; right:0;  bottom:0px;top:0px;opacity:0.8;filter:alpha(opacity=70);background: #ff2a61;width:94px;height:27px;border:1px solid #bfbfbf;text-decoration: none;color:white;margin:auto;border-radius: 4px; text-align:center;z-index:999999;}
.btn_bt_a{position: absolute;margin:auto;left:0; right:0;  bottom:0px;top:0px;width:97px; height:15px;font-size: 14px;color:white;text-decoration: none;text-align: center;z-index:99999999;}
.container{overflow:auto;-webkit-overflow-scrolling:touch;}
.item_area{width: 95%;background-color: #f5f5f5; padding: 5px; margin: 3px;}
.item_img{padding: 1px; display: inline-block; margin-right: 5px; vertical-align:middle;}
.item_title{display: inline-block; width: 220px;}
.price_text{width: 95%; text-align: right;}
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
        <br>商品价格：￥<span class="wechatcolor"><?php echo $auction['curr_price'];?> 订单金额：￥<span class="wechatcolor"><?php echo $auction['curr_price']-$auction['discount'];?></span>
      </div>
  <h3 style="padding-left:10px;">请填写收货信息</h3>
  <h3 style="padding-left:10px;color:red;"><?php echo $err_msg;?></h3>
    <section class="input_one algin_center">
        <form id="consignee_form" action="/mm/?r=showItem/orderCheck&item_id=<?php echo $item['id'];?>&token=<?php echo $token;?>&_dc=<?php echo time();?>" method="post" style="padding-left:10px;padding-right:10px;">
          <input name="consignee[auction_id]" id="consignee_auction_id" type="hidden" value="<?php echo $auction['id'];?>">
          <input name="consignee[item_id]" id="consignee_item_id" type="hidden" value="<?php echo $item['id'];?>">
            <input placeholder="收货人" maxlength="12" name="consignee[name]" id="consignee_name" type="text" value="<?php echo $consignee['name'];?>">
            <br>
            <select id="consignee_province" name="consignee[province]"></select>
            <br>
            <select id="consignee_city" name="consignee[city]"></select>
            <br>
            <input placeholder="地址" maxlength="256" name="consignee[address]" id="consignee_address" type="text" value="<?php echo $consignee['address'];?>">
            <br>
            <input placeholder="联系电话" maxlength="11" name="consignee[mobile]" id="consignee_mobile" type="text" value="<?php echo $consignee['mobile'];?>">
            <br>
            <select id="consignee_delivery_time" name="consignee[delivery_time]">
              <option>周一到周五工作时间</option>
              <option>周六周日双休日</option>
            </select>
        </form>
    </section>
    </div>
<br><br>
	</div>
<footer>
 <div class="downbtn"></div>
 <div class="btn_bt"></div>
 <a id="consignee_submit" class="btn_bt_a">提交订单</a>
</footer>
 </body>
</html>
<script type="text/javascript">
    AreaSelector.bind('consignee_province', 'consignee_city', '<?php echo $consignee['province'];?>', '<?php echo $consignee['city'];?>');

    $("#consignee_submit").click(function(){
      $("#consignee_form").submit();
    });
</script>
