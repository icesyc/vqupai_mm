<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--我的战况</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/share.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="killend/js/killend.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <style>
        html,body{background: #dfe2d6;}
    </style>
</head>
<body>
<div id="top">
    <?php $this->widget('AdWidget');?>

    <section class="icon">
      <aside>
          <ul>
              <li><input type="button"  class="icon_return" value="返回列表" /></li>
              <li><input type="button"  class="icon_store" value="收藏血战" /></li>
              <li><input type="button"  class="icon_share" value="分享帮杀" /></li>
              <div class="clear"></div>
          </ul>
      </aside>
        <article class="article">
            *点击右上角“…”在弹出菜单里选择“收藏”，即可随时在微信收藏中查看当前血战进度哦~
        </article>
        <article class="article hidden" >
            *点击右上角“…”在弹出菜单里选择“分享到朋友圈”， 就可以让朋友们帮你杀价啦！
        </article>
    </section>
    <div class="contain_new">
        <section class="bar_new">
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
                        <span class="right">底价：<?php echo $data['reserve_price']?></span><br>
                    </li>
                    <?php if($user && $user['id'] == $data['uid'] && $data['status'] < UserAuction::STATUS_DEAL):?>
                    <li>
                        <span class="left"><input type="button" class="status orderBtn j-order" value="立刻下单" /></span>
                        <span class="left"><input type="button" class="daoju j-prop" value="使用道具" /></span>
                    </li>
                    <?php endif ?>
                </ul>
            </div>
        </section>
        <section class="bar_see"><a href="index.php?r=item/show&id=<?php echo $data['item']['id']?>">查看商品图文详情</a></section>
        <section class="share_container">
             <div class="bar_dest colorff2">有<?php echo $data['helper_count'] ?>个小伙伴出手了</div>
             <div class="share_btn j-share-btn"></div>
             <?php foreach($data['helpers'] as $helper):?>
             <div class="friend">
                 <aside class="bottom1">
                     <ul>
                         <li style="text-align: right;padding-right:5px;padding-top:15px;"><?php echo $helper['user']['nick'];?></li>
                         <li style="padding-left:25px;width:20%;"><img src="<?php echo $helper['user']['avatar'];?>" width="40" /></li>
                         <li style="text-align: left;width:40%;padding-top:15px;"><?php echo $helper['discount']?>元&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $helper['ctime']?></li>
                         <div class="clear"></div>
                     </ul>
                 </aside>
                 <div class="clear"></div>
             </div>
            <?php endforeach?>
         </section>
    </div>
</div>
</div>
<div class="downbtn"></div>
<div class="logo_bt float_left"></div>
<div class="text_bt float_left">不够爽？来APP杀个痛快！</div>
<div class="btn_bt float_right"></div>
<a href="http://www.vqupai.com/d.php?s=wap&c=1&uid=<?php echo $stat['uid']?>" class="btn_bt_a">立刻下载</a>
<div class="dialog"></div>
<img src="images-share/text_dis.png" class="dialog_text" />

<div class="dialog-v1 j-no-buy">
    <h2>无法完成购买</h2>
    <div class="content">
        <p>限于微信内无法完成支付，请下载app后在app内完成下单操作。</p>
    </div>
    <a href="http://www.vqupai.com/d.php?s=wap&c=2&uid=<?php echo $stat['uid'];?>" class="dialog-btn">立即下载app购买</a>
</div>
<div id="msg_tip"></div>
<script type="text/javascript">
$(function(){
    $('.dialog,.dialog_text').show();
    $('.dialog,.dialog_text').click(function(){
        $('.dialog,.dialog_text').hide();
    })
    $('.icon_store').click(function(){
        $('.article').eq(0).show().next().hide();
    })
    $('.icon_share').click(function(){
        $('.article').eq(1).show().prev().hide();
    })
    $('.icon_return').click(function(){
        location.href = '?r=killEnd';
    });
})

var auction_id = <?php echo $data['id'];?>;
var can_buy = <?php echo $data['canBuy']?>;

//分享按钮点击
$('.j-share-btn').click(function(){
    $('.dialog,.dialog_text').show();
});

//下单和使用道具
$('.j-order').on('click', function(e){
    if(!can_buy){
        return $('#msg_tip').toast('该商品需要杀到底价才能购买');
    }
    $('.j-no-buy').popup();
});

$('.j-prop').on('click', function(e){
    location.href = 'index.php?r=myProp&auction_id=' + window.auction_id;
});

var stat = "<?php echo http_build_query($stat);?>";
var img = new Image;
img.src = 'http://www.vqupai.com/ver/i.gif?' + stat + '&_=' + Math.random();

function reportWapShare(){
  var url="/ver/i.gif?report=js&c=WapShare&<?php echo http_build_query($stat)?>";
  var img = new Image;
  img.src = url + '&_=' + Math.random();
}

wx.config(<?php echo json_encode(Yii::app()->session['wxconfig']); ?>);
wx.ready(function(){
  var wxData={
    imgUrl:"http://www.vqupai.com/<?php echo $data['item']['pic_cover'];?>",
    link:"http://www.vqupai.com<?php echo $_SERVER['PHP_SELF']?>?r=userAuction&id=<?php echo $data['id'];?>&wap_share=1",
    desc:"人多力量大！各位亲朋好友，快来帮我把它杀到<?php echo $data['reserve_price'];?>元吧！（猛戳这里）",
    title:"微趣拍血战到底，大家一起来杀价！",
    success: function(){
      reportWapShare();
    }
  };
  wx.onMenuShareTimeline(wxData);
  wx.onMenuShareAppMessage(wxData);
});
</script>
</body>
</html>