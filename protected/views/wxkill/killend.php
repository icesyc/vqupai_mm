<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--血战到底</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="killend/css/style.css"/>
    <script type="text/javascript" src="killend/js/zepto.js"></script>
    <script type="text/javascript" src="killend/js/killend.js"></script>
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
    <?php foreach($data as $row):?>
    <div class="list">
        <section class="bar_new">
            <img src="http://m.vqupai.com/<?php echo $row['item']['pic_cover']?>" width="140" />
            <aside class="text">
                <ul>
                    <li class="text" style="color:#3a3e3f;font-size:14px;line-height: 16px;">
                       <?php echo $row['item']['title']?>
                    </li>
                    <li>血战时限：<?php echo $row['duration']?>小时</li>
                    <li>市场价：<?php echo $row['item']['oprice']?></li>
                    <li>开杀价：<?php echo $row['start_price']?></li>
                    <li>底价：<?php echo $row['reserve_price']?></li>
                    <?php if($row['selfId'] > 0):?>
                    <li><span class="floatleft"><a href="index.php?r=killEnd/view&id=<?php echo $row['selfId']?>" class="btn_create j-view" style="margin-top:0px;">我的实况</a></span></li>
                    <?php else: ?>
                    <li><span class="floatleft"><a href="#" class="btn_create j-create" style="margin-top:0px;" data-pool-id="<?php echo $row['id']?>"/>创建血战</a></span></li>
                    <?php endif ?>
                    <div class="clear"></div>
                </ul>
            </aside>
        </section>
    </div>
    <?php endforeach ?>
</div>
<div class="dialog">
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
</script>
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