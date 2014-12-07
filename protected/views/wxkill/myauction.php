<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--我的血战</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="killend/css/style.css" />
    <script type="text/javascript" src="killend/js/zepto.js"></script>
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
    <div class="alert-tip" style="text-align:left">小拍提示：鉴于很多玩友反馈【“已杀至一元”，结果显示无库存】，在此温馨提示玩友们：当小拍提示您库存告急时，您需要及时下单哦，库存有限滴~小拍祝玩友们，玩得愉快！</div>
    <?php if(count($data) > 0):?>
    <?php foreach($data as $auction): ?>
    <section class="my_bar j-auction-item" data-id="<?php echo $auction['id']?>">
        <div class="orderlist" data-orderlist="0">
            <aside class="left">
                <ul>
                    <li>当前价格：<span style="color:#e76049"><?php echo $auction['curr_price']?></span></li>
                    <li>起始价格：<?php echo $auction['start_price']?></li><li>开始时间：<?php echo $auction['start_time']?></li>
                    <li>剩余时间：<?php echo $auction['left_time_text']?></li>
                </ul>
            </aside>
            <aside class="right">
                <ul>
                    <li><?php echo $auction['help_num']?>人帮拍</li>
                </ul>
            </aside>
            <div style="clear:both"></div>
        </div>
        <div class="order_dis">
            <img src="<?php echo $auction['item']['pic_cover']?>" class="card-img">
            <div class="right"><?php echo $auction['item']['title']?></div>
            <div style="clear:both"></div>
        </div>
    </section>
    <?php endforeach ?>
    <?php else:?>
        <p class="no-auction">您还没有创建血战，赶紧参战吧！</p>
        <a class="btn" href="index.php?r=killEnd">点击这里参加血战</a>
    <?php endif?>
</div>
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