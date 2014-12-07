<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">  
	<title>微趣拍 -- <?php echo $model->item->title;?> -- 开启乐趣非凡的拍卖之旅</title>
	<style type="text/css">
	body {
		font-family: 华文细黑;
		font-size: 10pt;
		line-height: 15px;
		background-color: #ffffff;
		margin: 0px;
		padding: 0px;
	}
	a {
		color: #c60;
		text-decoration:underline;
	}
	a:hover {
		color: #0895b3;
		text-decoration:none;
	}
	a.beian {
		color: #ffffff;
	}

	#outTable {
		width: 100%;
		margin: 0px;
		padding: 0px;
		border-spacing: 0px;
	}
	#contentTable {
		width: 320px;
		margin: 0px;
		padding: 0px;
		border-spacing: 0px;
	}
	img {
		margin: 0px;
		padding: 0px;
		vertical-align: middle;
	}
	table {
		border-spacing: 0px;
		border: 0px;
	}
	table td{
		padding:0;
	}
	.tipsSpan {
		border: 0px;
		color: #c60;
		line-height: 15px;
	}
	.tipsTd {
		background-color: #fff5cd;
		padding: 5px;
	}
	#footer {
		height: 60px;
		background-image: url(images/bottom.png);
		font-size: 7px;
		color: #ffffff;
		line-height: 20px;
	}
	#headPic {
		display: block;
	}
	</style>
<style type="text/css">
.item_title {
	font-size: 14pt;
	line-height: 30px;
}
.price {
	font-size: 8pt;
}
.mprice {
	text-decoration:line-through;
}
.cprice {
	color: #ff0000;
	font-size: 14pt;
}
#item_img {
	max-width: 300px;
}
#content {
	position: relative;
	width: 100%;
	text-align: center;
}
#item_img_div {
	padding-top: 10px;
	margin-left: auto;
	margin-right: auto;
	line-height: 20px;
}
#item_text_div {
	text-align: left;
	padding: 10px;
}
</style>
</head>

<body>
<?php

	//var_dump($_SERVER['HTTP_USER_AGENT']);
	$ag = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($ag,'Android')) {
		$wxpic = 'android-icon2.png';
		$wxtz = 'android-icon1.png';
	}
	else {
		$wxpic = 'IOS-icon2.png';
		$wxtz = 'IOS-icon1.png';
	}

?>
<table id="outTable" cellspacing=0>
<tr>
<td align="center">
<table id="contentTable" cellspacing=0>
<tr>
<td align="center">
	<a href="http://www.vqupai.com/d.php?f=share"><img id="headPic" src="images/head.png" width="320" height="60"></a>
</td>
</tr>
<tr>
<td class="tipsTd">
<span class="tipsSpan">请点击上方的LOGO，<a class="dl" href="http://www.vqupai.com/d.php?f=share">下载并安装微趣拍</a>，即刻开启新鲜有趣的拍卖之旅。</span>
</td>
</tr>
<tr>
<td>

<div id="content">
	<div id="item_img_div">
		<p class="item_title">
			<?php echo $model->item->title;?>
		</p>
		<p class="price">
			市场价 ：￥<span class="oprice"><?php echo $model->purchase->oprice;?></span>
				/
				微趣拍价 ：￥<span class="cprice"><?php echo $model->curr_price;?></span>
		</p>
		<a href="#"><img id="item_img" src="<?php echo $model->item->pic_cover;?>"></a>
	</div>
	<div id="item_text_div">
		<p>
			<?php echo nl2br($model->item->description);?>
		</p>
	</div>
</div>
</td>
</tr>
<tr>
<td class="tipsTd">
<span class="tipsSpan">请点击页面上方的LOGO，<a class="dl" href="http://www.vqupai.com/d.php?f=share">下载并安装微趣拍</a>，即刻开启新鲜有趣的拍卖之旅。</span>
</td>
</tr>
<tr>
<td id="footer" align="center" valign="middle">
Copyright © 2014-2015 VQUPAI All Rights Reserved.
<br>
<a class="beian" href="http://www.miitbeian.gov.cn" target="_blank">京ICP备14007212号</a>
</td>
</tr>
</table>
</td>
</tr>
</table>

</body>
</html>
