<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $item['title'];?></title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="container wei">
    <div class="w_320 mg_auto">
       <div class="wechat_title">
           <div class="wechat_logo float_left"></div>
           <div class="float_left"><input type="button" class="wechat_down" onclick="location.href='http://www.vqupai.com/d.php'"/></div>
           <div class="clear"></div>
       </div>
       <div class="al_center">
          <h4 class="mg_t40 w_320"><?php echo $item['title'];?></h4>
          <div class="w_320 wechat ">市场价：￥<?php echo $item['oprice'];?>
          <?php if(isset($auction)):?> &nbsp;/   &nbsp;微趣拍价：￥<span class="wechatcolor"><?php echo $auction['curr_price'];?></span><?php endif;?></div>
          <div class="mg_t10"><img src="<?php echo $item['pic_top'];?>" width="300" height="300"> </div>
          <div class="wechatdis mg_auto w_320">
          <p>【商品信息】</p>
          <?php echo $item['content'];?>
          </div>
           <div class="al_center mg_t40">
               <input type="button" class="reg_btn" value="下载并安装"  onclick="location.href='http://www.vqupai.com/d.php'">
           </div>
       </div>
        <div class="wechat_title mg_t40">
        </div>
    </div>

</div>
</body>
</html>