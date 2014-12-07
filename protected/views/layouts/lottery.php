<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="/mm/bootstrap/css/bootstrap.min.css">
	<script type="text/javascript" src="/mm/bootstrap/js/bootstrap.min.js"></script>
	<title>微趣拍 -- 开启乐趣非凡的拍卖之旅</title>
	<style type="text/css">
	body {
		font-family: simhei;
		font-size: 10pt;
		background-color: #ffffff;
		margin: 0px;
		padding: 0px;
	}
	a {
		color: #515151;
		text-decoration:none;
		margin: 0px;
		padding: 0px;
	}
	a:hover {
		color: #0895b3;
		text-decoration:none;
	}
	#foot {
		margin-top: 20px;
		font-size: 6pt;
		font-family: Arial;
	}
	img {
		margin: 0px;
		padding: 0px;
	}
	#header {
		position: relative;
		height: 75px;
		width: 100%;
		margin: 0px;
		padding: 0px;
	}
	#logo {
		position: relative;
		display: inline-block;
		float: left;
	}
	#download {
		position: relative;
		top: 14px;
		display: inline-block;
		float: right;
		padding-right: 10px;
	}
	</style>

</head>

<body>

<div id="header" align="center">
	<div id="logo">
		<img src="images/lottery_head.png" width="320px" height="75px">
	</div>
</div>

	<?php echo $content; ?>

<fotter>
<div id="foot" align="center">
Copyright © 2014-2015 VQUPAI All Rights Reserved.
<br>
<a href="http://www.miitbeian.gov.cn" target="_blank">京ICP备14007212号</a>
</div>
</fotter>

</body>
</html>
