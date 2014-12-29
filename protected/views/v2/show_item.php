<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $item['title'];?></title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
	<script>
	
	  $(function(){
			  
			  var height=$(window).height();
              var c_height=height-35;
			   $(".container").css("height",height);

			   
			   })
        
	</script>
    <style type="text/css">
	html,body{height:100%;overflow:hidden;}
footer{position:absolute;bottom:-1px;height:35px;width:100%;}
.downbtn{position:relative;width:100%;height:35px;background:#000000;opacity:0.5;filter:alpha(opacity=50);line-height:35px;height:35px;z-index:99;text-align: center;}
.btn_bt{position:absolute;margin:auto;left:0px;right:0px;top:0px;bottom:0px;opacity:1;filter:alpha(opacity=1);background: #ff2a61;width:94px;height:27px;border:1px solid #bfbfbf;text-decoration: none;color:white;margin:auto;border-radius: 4px; text-align:center;z-index:999999;}
.btn_bt_a{position:absolute;left:0px;right:0px;top:0px;bottom:0px;margin:auto;width:97px; height:15px;font-size: 14px;color:white;text-decoration: none;text-align: center;z-index:99999999;}
.container{overflow:auto;z-index:1;-webkit-overflow-scrolling:touch;}   
.order_dis{width:100%;height:25px;margin:20px auto;background:#ff2a61;color:white;text-align:center;font-size:14px;padding-top: 8px;}
.wechatdis img{
max-width: 100%;
display: block;
border: none;
margin: 0;
padding: 0;
}
</style>
</head>
<body>
<div class="container wei">

    <div class="w_320 mg_auto">
       <div class="al_center">
          <h2 class="mg_t40 w_320"><?php echo $item['title'];?></h2>
          <div class="w_320 wechat ">市场价：￥<?php echo $item['oprice'];?> &nbsp;/   &nbsp;<?php if($auction['sale_id']==255){?>微趣拍付邮领用：<?php }else{?>微趣拍价格：<?php }?>￥<span class="wechatcolor"><?php echo $auction['curr_price'];?></span></div>
<?php if($auction['discount']!=0):?>
<div class="order_dis">
   <span>下单立减：<?php echo $auction['discount'];?> 元</span>
</div>
<?php endif;?>
          <div class="mg_t10"><img src="<?php echo $item['pic_top'];?>" width="320" height="200"> </div>
          <div class="wechatdis mg_auto w_320 noPadding">
          <p>【商品信息】</p>
          <?php echo $item['content'];?>
          </div>
       </div>
    </div>
</div>
<footer>
 <div class="downbtn"></div>
 <div class="btn_bt"></div>
 <a href="/mm/?r=showItem/order&auction_id=<?php echo $auction['id']?>&token=<?php echo $token;?>" class="btn_bt_a">立即下单</a>
</footer>
 </body>
</html>
