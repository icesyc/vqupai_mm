<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>杀价成功</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="js/WeixinApi.js"></script>
</head>
<body>
<div class="container">
    <div class="success content">
        <div class="w_320 mg_auto"><br>
          <div class="s_logo"></div>
          <div class="title">
                <div class="img">
                    <img src="<?php echo $user['avatar'];?>"  width="44" height="44" />
                </div>
                <div class="text">
                    <div class="float_left img"></div>
                    <div class="float_left box">十二万分感谢！你一出手就帮我杀掉<?php echo $discount;?>元！现在“<?php echo $item['title'];?>”的当前价是：</div>
                </div>
                <div class="clear"></div>
          </div>
          <div class="price">￥<?php echo $user_auction['curr_price'];?></div>
        </div>

        <div class="explain">
            <div class="text pd_l10 color_31">现在下载微趣拍，注册就送10元拍券</div>
            <div class="text pd_l10 color_33">拍券在购物时抵现金用</div>
            <div class="text pd_l10 color_31">分享到朋友圈还可以请更多朋友来一起杀价哦~</div>
        </div>
        <div class="success_mg">
               <input type="button" value="登陆微趣拍" class="button mg_l27"  onclick="location.href='http://www.vqupai.com/d.php'"/>
        </div>
        <div class="explain mg_t10">
            <div class="text pd_l10 color_oob ">这是一个新奇有趣的购物方法，底价宝贝等你领回家！</div>
        </div>
        <div class="success_mg">
            <?php foreach($items as $it):?>
            <div class="float_left mg_l37" ><a href="?r=item/show&id=<?php echo $it['id'];?>"><img src="<?php echo $it['pic_cover'];?>" width="50" /></a></div>
            <?php endforeach;?>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <div class="logo"></div>
    </div>
</div>
<script>
WeixinApi.ready(function(Api){
  var wxData={
    imgUrl:"http://www.vqupai.com/<?php echo $item['pic_cover'];?>",
    link:"http://www.vqupai.com/mm/index.php?r=userAuction&id=<?php echo $user_auction['id'];?>&second_share=1",
    desc:"人多力量大！各位亲朋好友，快来帮我把它杀到<?php echo $user_auction['reserve_price'];?>元吧！（猛戳这里）",
    title:"微趣拍血战到底，大家一起来杀价！"
  };

  

  var wxCallbacks={
    ready:function(){
      ;
    },
    cancel:function(resp){
      ;
    },
    fail:function(resp){
      ;
      
    },
    confirm:function(resp){
      ;
    },
    all:function(resp){
      ;
    }
  };

  Api.shareToFriend(wxData, wxCallbacks);
  Api.shareToTimeline(wxData, wxCallbacks);

});
</script>
</body>
</html>
