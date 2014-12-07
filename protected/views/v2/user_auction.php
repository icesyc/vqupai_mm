<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>帮我杀价</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="js/WeixinApi.js"></script>
</head>
<body>
<div class="container">
    <div class="top">
        <div class="w_320 mg_auto">
          <div class="content">
              <div class="title">
                  <div class="img">
                      <img src="<?php echo $user['avatar'];?>"  width="44" height="44" />
                  </div>
                  <div class="text">
                      <div class="float_left img"></div>
                      <div class="float_left box">我看上了“<?php echo $item['title'];?>”兄弟姐们 快来帮我杀价吧！</div>
                  </div>
                  <div class="clear"></div>
              </div>
              <div class="product">
                  <div class="pro">
                      <div class="img float_left"><img src="<?php echo $item['pic_cover'];?>" width="160" /></div>
                      <div class="shadow float_left"></div>
                  </div>
                  <div class="bg"></div>
                  <div class="clear"></div>
              </div>
              <div class="btn">
                  <div class="price float_left mg_l10" style="margin-left:16px;">
                      <div class="left float_left">现价</div>
                      <div class="right float_left"><?php echo $auction['curr_price'];?></div>
                  </div>
                  <div class="arrow float_left mg_l10"></div>
                  <div class="float_left circle mg_l10">
                      <div class="img" id="helpBtn"></div>
                  </div>
                  <div class="arrow float_left mg_l10"></div>
                  <div class="price float_left mg_l10">
                      <div class="left float_left bg_00">底价</div>
                      <div class="right float_left bg_ff2"><?php echo $auction['reserve_price'];?></div>
                  </div>
                  <div class="clear"></div>
              </div>

          </div>
        </div>
        <div class="explain">
            <div class="title">玩法介绍</div>
            <div class="text">您的朋友在微趣拍里发现了这个好东东，TA需要您的帮助，您点击杀价，就可以帮助TA杀掉一部分价格，一切都是免费的，勇敢地按下您的手指吧！</div>
        </div>
        <div class="clear"></div>
        <div class="logo"></div>
    </div>
</div>
<input type="hidden" id="auctionId" value="<?php echo $auction['id'];?>"/>
<div id="msg_tip"></div>
<script type='text/javascript'>
var msgTimer;
var aid = $('#auctionId').val();
$('#helpBtn').on('click', ajaxHelp);
function ajaxHelp(){
  var url = '?r=UserAuction/help&id=' + aid;
  $.get(url, function(rsp){
    if(rsp.success){
      location.href='?r=UserAuction/success&id=' + aid + '&discount=' + rsp.discount;
      return;
    }
    $('#msg_tip').text(rsp.msg).fadeIn(300, function(){
      if(msgTimer){
        clearTimeout(msgTimer);
        msgTimer = null;
      }
      msgTimer = setTimeout(function(){
        $('#msg_tip').fadeOut(300);
      }, 3000);
    });
  });
}

WeixinApi.ready(function(Api){
  var wxData={
    imgUrl:"http://www.vqupai.com/<?php echo $item['pic_cover'];?>",
    link:"http://www.vqupai.com/mm/index.php?r=userAuction&id=<?php echo $auction['id'];?>&second_share=1",
    desc:"人多力量大！各位亲朋好友，快来帮我把它杀到<?php echo $auction['reserve_price'];?>元吧！（猛戳这里）",
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
