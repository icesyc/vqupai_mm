<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--血战到底</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="killend/css/style.css"/>
</head>
<body>
<div class="bar">
    <div class="title_down">
        <a class="download" href="http://www.vqupai.com/d.php?s=wap&c=3&uid=<?php echo $user['id'];?>">
            <div class="btn"></div>
        </a>
    </div>
    <section class="bar_title">
        <div><a class="btn_list color_e7" href="index.php?r=scoreRule">积分规则</a></div>
        <div><a class="color_e7 btn_my" href="index.php?r=killEnd/my">我的战况</a></div>
        <div><a class="color_e7 btn_help" href="index.php?r=killHelp">血战说明</a></div>
    </section>
    <ul class="help-img">
        <li><img src="killend/images/help1.png"/></li>
        <li><img src="killend/images/help2.png"/></li>
        <li><img src="killend/images/help3.png"/></li>
        <li><img src="killend/images/help4.png"/></li>
    </ul>
</div>
<script type="text/javascript">
var stat = "<?php echo http_build_query($stat);?>";
var img = new Image;
img.src = 'http://www.vqupai.com/ver/i.gif?' + stat + '&_=' + Math.random();
</script>
</body>
</html>