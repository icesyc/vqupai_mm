<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--我的道具</title>
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
<div class="bar">
    <?php $this->widget('AdWidget');?>

    <?php foreach($propList as $prop):?>
    <section class="prop-list j-prop-item" data-id="<?php echo $prop['prop']['id']?>">
        <div class="fleft <?php echo $prop['prop']['action']?>"></div>
        <div class="prop-desc">
            <div class="name"><?php echo $prop['prop']['name']?> * <?php echo $prop['num']?></div>
            <div class="desc"><?php echo $prop['prop']['description']?></div>
        </div>
    </section>
    <?php endforeach ?>
    <section>
        <div class="plist_shuo">
            <ul>
                <li style="margin:10px 0px;">
                    <span>道具使用规则：</span>
                </li>
                <li>* 在参与血战到底的时候可以使用道具</li>
                <li>* 每个独立的血战同一种道具只可以使用1次</li>
                <li>* 道具可以通过积分兑换得来</li>
            </ul>
        </div>
    </section>
    <a class="prop-btn" href="http://www.vqupai.com/d.php">立即下载app赚取积分</a>
</div>
<div id="msg_tip"></div>
<script type="text/javascript">
var auction_id = <?php echo $auction_id ?>;
$('.j-prop-item').on('click', function(e){
    var url = 'index.php?r=myProp/use&prop_id=' + $(this).data('id') + '&auction_id=' + window.auction_id;
    $.get(url, function(rsp){
        $('#msg_tip').toast(rsp.msg);
        if(rsp.success){
            setTimeout(function(){
                location.href = 'index.php?r=killEnd/view&id=' + window.auction_id;
            }, 2000);
        }
    });
});

var stat = "<?php echo http_build_query($stat);?>";
var img = new Image;
img.src = 'http://www.vqupai.com/ver/i.gif?' + stat + '&_=' + Math.random();
</script>
</body>
</html>