<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--血战到底</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/share.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="killend/js/killend.js"></script>
    <style>
        html,body{background: #dfe2d6;}
    </style>
</head>
<body>

<section id="top">
    <?php $this->widget('AdWidget');?>

    <div class="contain_new">
       <?php if($mykill):?>
       <section class="my_bar" data-url="index.php?r=killEnd/view&id=<?php echo $mykill['id']?>">
           <div class="my_bar_title"><span>我的血战</span></div>
           <div class="img"><img src="http://m.vqupai.com<?php echo $mykill['item']['pic_cover']?>" width="50" /></div>
           <div class="my_bar_content">
               <div class="title"><?php echo $mykill['item']['title']?></div>
               <div class="colorff2 price"><span>当前价:<?php echo $mykill['curr_price']?>  </span>底价:<?php echo $mykill['reserve_price']?></span></div>
               <a class="btn_icon"><div class="mg_t5">点击进入</div></a>
           </div>
           <div class="clear"></div>
       </section>
       <?php endif;?>
       
       <?php foreach($data as $row):?>
       <section class="bar_list">
           <img class="img" src="http://m.vqupai.com<?php echo $row['item']['pic_cover']?>" width="130" />
           <div class="text">
               <div class="title"><?php echo $row['item']['title']?></div>
               <div class="time">血战时限：<?php echo $row['duration']?>小时</div>
               <div class="time">开杀价：<?php echo $row['start_price']?></div>
               <div class="colorff2 price" >底价：¥<span class="font_25"><?php echo $row['reserve_price']?></span></div>
               <div class="icon_create">
                <?php if($row['selfId'] > 0):?>
                    <a href="index.php?r=killEnd/view&id=<?php echo $row['selfId']?>" class="j-view" data-pool-id="<?php echo $row['id']?>">我的实况</a>
                    <?php else: ?>
                   <a href="#" class="j-create" data-pool-id="<?php echo $row['id']?>">创建血战</a>
                    <?php endif ?>
               </div>
           </div>
           <div class="clear"></div>
       </section>
       <?php endforeach ?>
    </div>
</section>
<div class="downbtn"></div>
<div class="logo_bt float_left"></div>
<div class="text_bt float_left">不够爽？来APP杀个痛快！</div>
<div class="btn_bt float_right"></div>
<a href="http://www.vqupai.com/d.php?s=wap&c=3&uid=<?php echo $stat['uid'];?>" class="btn_bt_a">立刻下载</a>


<div class="dialog-v1 j-no-score">
    <h2>您的积分不足</h2>
    <div class="content">
        <p>您的积分是{userScore}</p>
        <p>创建一次血战需要{costScore}</p>
    </div>
    <a href="http://www.vqupai.com/d.php?s=wap&c=2&uid=<?php echo $stat['uid'];?>" class="dialog-btn">立即下载app赚积分</a>
</div>
<div id="msg_tip"></div>
<script type="text/javascript">
var stat = "<?php echo http_build_query($stat);?>";
var img = new Image;
img.src = 'http://www.vqupai.com/ver/i.gif?' + stat + '&_=' + Math.random();
$('.my_bar').click(function(){
  location.href = $(this).data('url');
});
</script>
</body>
</html>