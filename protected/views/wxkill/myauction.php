<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--我的血战</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/share.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <style>
        html,body{background: #dfe2d6;}
    </style>
</head>
<body>

<section id="top" style="margin-bottom:35px">
    <?php if(count($data) > 0):?>
    <?php foreach($data as $auction): ?>
    <section class="myauction j-auction-item" data-id="<?php echo $auction['id']?>">
        <div class="orderlist" data-orderlist="0">
            <div class="fleft">
                <ul>
                    <li>当前价格：<span style="color:#e76049"><?php echo $auction['curr_price']?></span></li>
                    <li>起始价格：<?php echo $auction['start_price']?></li><li>开始时间：<?php echo $auction['start_time']?></li>
                    <li>剩余时间：<?php echo $auction['left_time_text']?></li>
                </ul>
            </div>
            <div class="fright">
                <ul>
                    <li><?php echo $auction['help_num']?>人帮拍</li>
                </ul>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="order_dis">
            <img src="<?php echo $auction['item']['pic_cover']?>" class="card-img">
            <div class="fright"><?php echo $auction['item']['title']?></div>
            <div style="clear:both"></div>
        </div>
    </section>
    <?php endforeach ?>
    <?php else:?>
        <p class="no-auction">您还没有创建血战，赶紧参战吧！</p>
        <a class="btn" href="index.php?r=killEnd">点击这里参加血战</a>
    <?php endif?>
</section>
<div class="downbtn"></div>
<div class="logo_bt float_left"></div>
<div class="text_bt float_left">不够爽？来APP杀个痛快！</div>
<div class="btn_bt float_right"></div>
<a href="http://www.vqupai.com/d.php?s=wap&c=3&uid=<?php echo $stat['uid'];?>" class="btn_bt_a">立刻下载</a>
<script type="text/javascript">
$('.j-auction-item').on('click', function(){
    location.href = 'index.php?r=killEnd/view&id=' + $(this).data('id');
});
var stat = "<?php echo http_build_query($stat);?>";
var img = new Image;
img.src = 'http://www.vqupai.com/ver/i.gif?' + stat + '&_=' + Math.random();
</script>
</body>
</html>