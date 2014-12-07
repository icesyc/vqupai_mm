<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>微趣拍--iphone6抽奖</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="css/act_iphone.css" />
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
    <link rel="shortcut icon" href="vqupai.ico" />
</head>
<body>
<div class="contain">
    <section class="img_two">
        <input class="input" id="phone" type="text"  placeholder="输入手机号码参与抽奖" maxlength="11" />
        <a class="btn" id="submit"/></a>
    </section>
<div class="err_msg" style="font-size:18px;"><?php echo $err_msg;?></div>
    <section class="intro_text">
        <ul>
            <li class="algin_center">活动时间：<font color="#009c84">2014年10月28日－－2014年12月31日</font></li>
            <li class="algin_center">抽奖细则</li>
            <li>每一位微趣拍的注册用户，在活动期间，凭借有效手机号码，均可获得一次免费抽奖，<font color="#009c84">每30天为一个抽奖周期</font>，直至活动结束为止。</li>
            <li><font color="#009c84">请不要重复参与抽奖</font>，同一个用户多个手机号或者一个手机号多个用户，均被视为违规。</li>
            <li>每期在参与用户中，随机抽取一位拍友，免费获得由微趣拍老板挥泪送出的<font color="#009c84">iphone6一部</font>，没有看错，是免费的，而且每期送，我是一定会在线等的，每天都看着！</li>
            <li>其他没有抽中的拍友可以随机获得拍券、5元－100元充值卡等暖心礼物，所以看到这里，小拍愉快多了，总是木有白等的，快来参加吧，结束单肾贵族的生活，寻找真爱呗～
            </li>
            <li></li>
            <li></li>
            <li></li>
            <li>注：每个周期的暖心奖品将于10个工作日内统一邮寄或配发，请您耐心等待。请您务必确保信息正确，如因获奖者未能及时填写有效信息造成小拍联系不到您，奖品无法领取，责任由获奖者自负，话费奖品将直接充值到用户手机。</li>

            <li class="algin_center"> <font color="#009c84">严禁刷奖行为，一经发现取消中奖资格。</font></li>
            <li class="algin_center"> 本次活动的解释权归主办方微趣拍所有</li>
        </ul>
    </section>
</div>
</body>
</html>
<script type="text/javascript">
$("#submit").click(function(){
	phone=$("#phone").val();
	url="/mm/?r=actIphone/check&token=<?php echo $token;?>&phone="+phone;
	document.location.href=url;
});
</script>