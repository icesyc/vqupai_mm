<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--分享战果</title>
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
                <div>感谢各位拔刀相助！这是一起努力的战果</div>
            </div>
            <div class="clear"></div>
        </article>
        <div class="clear"></div>
        <div class="killimg">
            <img src="images-share/kill_success.jpg" width="282" height="96" />
        </div>
        <aside class="font_13">
            <ul>
                <li class="color364 align_center font_18">"<?php echo $item['title'];?>"</li>
                <li>
                    <div class="product">

                       <img src="<?php echo $item['pic_cover']?>" width="150" height="150"  class="img" />
                        <div class="pro_dialog"></div>
                    </div>
                    <div class="pro_zhe">最终战果<br><div class="font_18 pad_t5"><?php echo $auction['curr_price'];?></div></div>
                    <div class="clear"></div>
                </li>
                <li class="align_center"><a href="<?php echo $this->killEndUrl;?>"><input type="button" class="btn_kill2"/></a><br></li>
                <li></li>
                <li></li>

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
<div class="downbtn"></div>
<div class="logo_bt float_left"></div>
<div class="text_bt float_left">不够爽？来APP杀个痛快！</div>
<div class="btn_bt float_right"></div>
<a href="http://www.vqupai.com/d.php?s=wap&c=3&uid=<?php echo $user['id'];?>" class="btn_bt_a">立刻下载</a>
<script type="text/javascript">
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
    link:"http://www.vqupai.com/mm/index.php?r=userAuction&id=<?php echo $auction['id'];?>&second_share=1",
    desc:"谢谢各位亲朋好友！我们把它杀到了<?php echo $auction['curr_price'];?>元！（猛戳这里）",
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