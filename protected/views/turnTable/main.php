<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>微趣拍-转盘抽奖</title>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<style type="text/css">
html,body{padding:0px;margin:0px;font-size:12px;}
body{background: #efefef;}
.color_e7{color:#e76049;}
.color_a8{color:#a8a3b1;}
.font_bold{font-weight: bold;}

.ad{background: white;padding:5px 10px;margin-bottom: 10px;}
.img{width:100%;min-width: 300px;margin:auto;}
.sign{width:100%;height:62px;background: white;}
.sign .title{padding:5px 10px;}
.sign .title .left_img{background-size: contain;width:50px;height:50px;float:left;}
.sign .title .left_img .grade{color:#ffed24;margin-top:-15px;text-align: center;text-shadow: 1px 0 1px #ea7462, 0 1px 1px #ea7462,0 -1px 1px #ea7462, -1px 0 1px #ea7462;}
.sign .title .right_text{margin-left: 50px;}
.sign_data{font-size:14px;margin-top:7px;}
.sign_data span{padding-left:4px;}

.sign_des{margin:10px 10px 5px; line-height: 18px;word-break: break-all;word-spacing: normal;}
.sign_list{margin:0 10px 0px 5px;}
.sign_list ul{list-style-type: none;margin:0px;padding:0px;}
.sign_list ul li{float:left;margin-left:5px; border-radius:5px;background: white;margin-top:5px;width:33%;position:relative;}
.dialog{border-radius:5px;background:#999;opacity:0.6;filter:alpha(opacity=60);width:100%;height:100%;position: absolute;top: 0px;z-index: 99999999;display: none;}

@media only screen and  (min-width: 320px) and (max-width: 320px) {
    .sign_list ul li {
        width: 96.5px;
        height: 96.5px;
    }
}

@media only screen and  (min-width: 360px) and (max-width: 360px){
    .sign_list ul li {
        width: 110px;
        height: 110px;
    }
}
@media only screen and  (min-width: 375px) and (max-width: 375px){
    .sign_list ul li {
        width: 115px;
        height: 115px;
    }
}
@media only screen and  (min-width: 400px) and (max-width: 400px){
    .sign_list ul li {
        width: 123px;
        height: 123px;
    }
}
@media only screen and  (min-width: 414px) and (max-width: 1280px){
    .sign_list ul li {
        width: 128px;
        height: 128px;
    }
}
@media only screen and  (min-width: 384px) and (max-width: 384px){
    .sign_list ul li {
        width: 118px;
        height: 118px;
    }
}
@media only screen and  (min-width: 540px) and (max-width: 1280px){
    .sign_list ul li {
        width: 139px;
        height: 139px;
    }
}
@media only screen and  (min-width: 480px) and (max-width: 480px){
    .sign_list ul li{
    width:150px;
    height:150px;
}
}
.sign_title{font-weight: bolder;margin:5px 10px;border-bottom:1px solid black;padding-top:5px;pading-bottom:5px;text-align: center;}
.sign_content{text-align: center;margin-top:1px; font-size:12px;}
.sign_list ul li img{background-size:contain;width:100%;height:100%;position:absolute; display: none;}
.tab{margin:10px;border:1px solid #e76049;border-radius: 5px; color:#e76049;height:30px;background: white; }
.tab ul{list-style: none;padding:0px;margin:0px;}
.tab ul li{float:left;width:50%;height:35px;text-align: center;padding-top:10px;}
.tab ul li.active{background:#e76049;color:white;height:20px;padding-top:10px;}
.tab_content{width:100%;}

</style>
<script type="text/javascript" src="<?php Yii::app()->request->baseUrl;?>js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php Yii::app()->request->baseUrl;?>js/jQueryRotate.2.2.js"></script>
<script type="text/javascript" src="<?php Yii::app()->request->baseUrl;?>js/jquery.easing.min.js"></script>
<script type="text/javascript">
$(function(){
        $('.tab ul li').each(function(index,obj){
            $(this).click(function(){
                $(this).addClass('active').siblings().removeClass("active");
                $('.tab_content'+index).show().siblings().hide();
            })
        })
   

    })


</script>
</head>

<body>
<div class="ad">
    <img src="images_table/ad.png" class="img"/>
</div>
<div class="sign">
    <div class="title">
        <div class="left_img">
            <img src="<?php echo $this->getImageBySize($user->avatar, 140);?>" width="50">
           <!--  <div class="grade">Lv.<?php echo $user->level;?></div> -->
        </div>
        <div class="right_text">
            <div class="sign_data">
                <span><?php echo $user->nick;?></span>
                <span class="color_a8">该月已签到<label class="color_e7 qiandao">
                <?php echo $days ?>
                </label>天</span>
            </div>
            <div class="sign_data">
                <span class="color_a8">积分<label class="color_e7 score"><?php echo $user->score;?></label></span>
            <!--     <span class="color_a8">拍券<label class="color_e7">11</label>张</span> -->
                <span class="color_a8">经验值<label class="color_e7 exp"><?php echo $user->exp;?></label></span>
            </div>
        </div>
    </div>
</div>
<div class="tab">
    <ul>
        <li class="active">签到</li>
        <li>抽奖</li>
    </ul>
</div>
<div class="tab_content">

        <div class="tab_content0">
           <?php include_once './protected/views/turnTable/sign.php'; ?> 
        </div>
        <div class="tab_content1" style="display:none;">
         <?php include_once './protected/views/turnTable/index.php'; ?>
        </div>



    </div>


</body>
</html>