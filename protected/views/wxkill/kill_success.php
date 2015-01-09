<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--杀价成功</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/share.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="js/WeixinApi.js?v=4.1"></script>
</head>
<body>
<section id="top">
    <?php $this->widget('AdWidget');?>
    
    <div class="contain">
        <div class="dialog_dis"></div>
        <article class="fl"><img src="<?php echo $user['avatar'];?>"  width="46" height="46"/></article>
        <article class="dis_right">
            <div class="float_left discont">
                <div>好快的刀！好利的剑！</div>
            </div>
            <div class="clear"></div>
        </article>
        <div class="clear"></div>
        <div class="killimg">
            <img src="images-share/kill_success.jpg" width="282" height="96" />
        </div>
        <aside class="font_13">
            <ul>
                <li class="color364 align_center font_18">"<?php echo $discount;?>"</li>
                <li class="color364 align_center">当前价格</li>
                <li class="colorff2 align_center font_18"><?php echo $user_auction['curr_price'];?></li>
                <li style="width: 200px;margin:auto;text-align:center">
                    <a href="index.php?r=killEnd"><input type="button"  class="btn_kill3"/></a>
                </li>
                <li class="color364 align_center">立刻下载微趣拍APP，拥有自己的一元商品！</li>
                <li class="pro">
                    <?php foreach($items as $it):?>
                    <div><a href="?r=item/show&id=<?php echo $it['id'];?>"><img src="<?php echo $it['pic_cover'];?>" width="50" height="50" alt="<?php echo $it['title'];?>" /></a></div>
                    <?php endforeach;?>
                    <div class="clear"></div>
                </li>
            </ul>
        </aside>
        <div class="clear"></div>
    </div>
    <div class="bottom"><div class="dis_bottom"></div></div>
</section>
<section id="friend">
    <div class="color364 align_center">———— 有<?php echo $helper_count?>个小伙伴出手了 ————</div>
    <div class="friend_top"></div>
    <div class="friend_bg">
        <aside>
            <ul>
                <?php foreach($helpers as $helper):?>
                <li>
                    <div class="w_132 float_left" style="text-align: right;padding-right:5px;"><?php echo $helper['nick'];?></div>
                    <div class="friend_img float_left">
                        <img src="<?php echo $helper['avatar'];?>" width="20" height="20" />
                    </div>
                    <div class="w_132 float_left" style="padding-left:5px;"><span class="float_left"><?php echo $helper['discount'];?>元</span><span class="float_right color_555"><?php echo $helper['ctime']?></span></div>
                    <div class="clear"></div>
                </li>
                <?php endforeach;?>
            </ul>

        </aside>
    </div>
</section>
<div class="dialog"></div>
<div class="downbtn"></div>
<div class="logo_bt float_left"></div>
<div class="text_bt float_left">不够爽？来APP杀个痛快！</div>
<div class="btn_bt float_right"></div>
<a href="http://www.vqupai.com/d.php?s=wap&c=3&uid=<?php echo $user['id'];?>" class="btn_bt_a">立刻下载</a>
<script type="text/javascript">
$(function(){
    $(".dialog").show();
    $(".dialog_dis").show();
    $(".dialog,.dialog_dis").click(function(){
        $(".dialog").hide();
        $(".dialog_dis").hide();
    })
});

//上报数据
var url="/ver/i.gif?report=js&c=ua&<?php echo http_build_query($loger)?>";
var img = new Image;
img.src = url + '&_=' + Math.random();

function reportSecondShare(){
  var url="/ver/i.gif?report=js&c=SecondShare&<?php echo http_build_query($loger)?>";
  var img = new Image;
  img.src = url + '&_=' + Math.random();
}

WeixinApi.ready(function(Api){
  var wxData={
    imgUrl:"http://www.vqupai.com/<?php echo $item['pic_cover'];?>",
    link:"http://www.vqupai.com/mm/index.php?r=userAuction&id=<?php echo $user_auction['id'];?>&second_share=1",
    desc:"人多力量大！各位亲朋好友，快来帮我把它杀到<?php echo $user_auction['reserve_price'];?>元吧！（猛戳这里）",
    title:"微趣拍血战到底，大家一起来杀价！"
  };

  var wxCallbacks={
    confirm:function(resp){
      reportSecondShare();
    },
  };
  
    // 用户点开右上角popup菜单后，点击分享给好友，会执行下面这个代码
    Api.shareToFriend(wxData, wxCallbacks);

    // 点击分享到朋友圈，会执行下面这个代码
    Api.shareToTimeline(wxData, wxCallbacks);

    // 点击分享到腾讯微博，会执行下面这个代码
    Api.shareToWeibo(wxData, wxCallbacks);

    // iOS上，可以直接调用这个API进行分享，一句话搞定
    Api.generalShare(wxData,wxCallbacks);

});
</script>
</body>
</html>