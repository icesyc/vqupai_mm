<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $discount['title'];?></title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="container weixin">
    <div class="w_320 mg_auto">
        <div class="wechat_title">
            <div class="wechat_logo float_left"></div>
            <div class="float_left"><input type="button" class="wechat_down"  onclick="location.href='http://www.vqupai.com/d.php'"/></div>
            <div class="clear"></div>
        </div>
        <div class="we_sharedown mg_auto w_320">
            <div class="eat">
                <div class="float_left"><img src="<?php echo $discount['pic_url'];?>" width="110" /></div>
                <div class="float_left mg_l10 w_60ps">
                    <div class="sharetitle"><?php echo $discount['title'];?></div>
                    <div class="sharedis"><?php echo $discount['abstract'];?></div>
                    <div class="sharedis mg_t10">有效期 <?php echo $discount['expire_time'];?></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="wedetail mg_t10">
            <div class="wechatdis mg_auto">
                <div class="title"><strong>优惠详情</strong></div>
                <div class="mg_t10"><?php echo $discount['intro'];?></div>
            </div>
            <div class="wechat_share_btn al_center"><input type="button" class="reg_btn" value="点此下载领取"  onclick="location.href='http://www.vqupai.com/d.php'"></div>
            <div class="mg_t10 wechat_seller mg_auto">
                        <div class="title mg_t10"><strong>商家介绍</strong></div>
                        <div class="mg_t10"><?php echo $discount['description'];?></div>
            </div>
            <div class="wechat_title mg_t40 "></div>
        </div>
    </div>

</div>
</body>
</html>