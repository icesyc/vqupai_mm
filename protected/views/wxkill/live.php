<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--我的战况</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="killend/css/style.css" />
    <script type="text/javascript" src="killend/js/zepto.js"></script>
    <script type="text/javascript" src="killend/js/killend.js"></script>
    <script type="text/javascript" src="js/WeixinApi.js"></script>
    <script type="text/javascript" src="killend/js/bar.js"></script>
</head>
<body>
<div class="bar">
    <div class="title_down">
        <a class="download" href="http://www.vqupai.com/d.php?s=wap&c=3&uid=<?php echo $stat['uid'];?>">
            <div class="btn"></div>
        </a>
    </div>
    <section class="bar_title">
        <div><a class="btn_list color_e7" href="index.php?r=scoreRule">积分规则</a></div>
        <div><a class="color_e7 btn_my" href="index.php?r=killEnd/my">我的战况</a></div>
        <div><a class="color_e7 btn_help" href="index.php?r=killHelp">血战说明</a></div>
    </section>
<section class="bar_new" style="height: 115px;margin-top:-6px;">
    <img src="<?php echo $data['item']['pic_cover']?>" width="100" height="100"/>
    <div class="text">
        <ul>
         <li class="text"><?php echo $data['item']['title']?></li>
         <?php if($data['status'] > UserAuction::STATUS_ONLINE):?>
         <li><?php echo UserAuction::$status_list[$data['status']];?></li>
         <?php else: ?>
         <li>剩余时间:<?php echo $data['left_time_text']?></li>
         <?php endif ?>
         <li class="red">
            <span class="left">当前价格：<?php echo $data['curr_price']?></span>
            <span class="right">底价：<?php echo $data['reserve_price']?></span><br></li>
            <?php if($user && $user['id'] == $data['uid'] && $data['status'] < UserAuction::STATUS_DEAL):?>
            <li>
                <span class="left"><input type="button" class="status orderBtn j-order" value="立刻下单" /></span>
                <span class="left"><input type="button" class="daoju j-prop" value="使用道具" /></span>
            </li>
            <?php endif ?>
        </ul>
    </div>
</section>
<div class="alert-tip" style="transform:scale(0);-webkit-transform:scale(0);">货品有限，请及时下单！</div>
 <a class="bar_see color_e7" href="index.php?r=item/show&id=<?php echo $data['item']['id']?>">查看商品图文详情</a>
 <section class="share_container">
     <div class="bar_dest">有<?php echo $data['helper_count'] ?>个小伙伴出手了</div>
     <?php foreach($data['helpers'] as $helper):?>
     <div class="friend">
         <aside class="bottom">
             <ul>
                 <li style="text-align: right;padding-right:5px;padding-top:15px;"><?php echo $helper['user']['nick']?></li>
                 <li style="padding-left:10px;"><img src="<?php echo $helper['user']['avatar']?>" width="40" /></li>
                 <li style="text-align: left;padding-top:15px;"><?php echo $helper['discount']?>元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $helper['ctime']?></li>
                 <div class="clear"></div>
             </ul>

         </aside>
         <div class="clear"></div>
     </div>
    <?php endforeach?>
 </section>
 <div class="share-tips-mask"></div>
 <div class="share-tips"></div>
 <div class="dialog">
    <h2>无法完成购买</h2>
    <div class="content">
        <p>限于微信内无法完成支付，请下载app后在app内完成下单操作。</p>
    </div>
    <a href="http://www.vqupai.com/d.php" class="dialog-btn">立即下载app购买</a>
</div>
<div id="msg_tip"></div>
<script type="text/javascript">
var auction_id = <?php echo $data['id'];?>;
var can_buy = <?php echo $data['canBuy']?>;

//tips弹出动画
$(".alert-tip").animate({
    scale: 1
}, 500, 'ease-out');
setTimeout(function(){
    $(".alert-tip").hide();
}, 30000);
/*
$('.share_btn').on('click',function(){
    $(".share-tips").show();
    $(".share-tips-mask").css('display', 'block');
});
*/
$(".share-tips-mask,.share-tips").click(function(){
    $(".share-tips").hide();
    $(".share-tips-mask").css('display', 'none');
});

//下单和使用道具
$('.j-order').on('click', function(e){
    if(!can_buy){
        return $('#msg_tip').toast('该商品需要杀到底价才能购买');
    }
    $('.dialog').popup();
});

$('.j-prop').on('click', function(e){
    location.href = 'index.php?r=myProp&auction_id=' + window.auction_id;
});

WeixinApi.ready(function(Api){
  var wxData={
    imgUrl:"http://www.vqupai.com/<?php echo $data['item']['pic_cover'];?>",
    link:"http://www.vqupai.com/mm/index.php?r=userAuction&id=<?php echo $data['id'];?>&second_share=1",
    desc:"人多力量大！各位亲朋好友，快来帮我把它杀到<?php echo $data['reserve_price'];?>元吧！（猛戳这里）",
    title:"微趣拍血战到底，大家一起来杀价！"
  };
  Api.shareToFriend(wxData);
  Api.shareToTimeline(wxData);
});

var stat = "<?php echo http_build_query($stat);?>";
var img = new Image;
img.src = 'http://www.vqupai.com/ver/i.gif?' + stat + '&_=' + Math.random();
</script>
</div>
<div class="dialog2">
</div>
<div class="text_dialog">
    <div class="text">下载安装微趣拍APP，在APP内杀价<span class="price_dialog">额度更大</span>，玩儿得更爽！</div>
    <div class="weixin">记得要用微信登陆哦～</div>
    <div class="dialog_cont">
        <div class="btn3" ><a href="" >再玩玩看</a></div>
        <div class="btn1"><a href="http://www.vqupai.com/d.php?s=wap&c=1&uid=<?php echo $stat['uid'];?>" >我要更爽</a></div>
    </div>
</div>
</body>
</html>