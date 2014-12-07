<div class="success">
	<section>&nbsp;</section>
	<section class="text">
		<div style="text-align: center;display: none;" id="onload_success">
让更多的小伙伴知道这个好消息，先分享给朋友或朋友圈，再领取奖品哦!
		</div>
		<div style="text-align: center;" id="onload_error">
页面加载失败，请刷新页面。
		</div>
	</section>
</div>

<script>
var successRedirectToUrl="<?php echo Yii::app()->createUrl('activitySep/success'); ?>";
var actionShareSuccess=function(){
	window.location.href=successRedirectToUrl;
};

var wxShareCover="http://www.vqupai.com/images/about_logo.png";
var wxShareLink="http://www.vqupai.com/mm/index.php?r=activitySep";
var wxShareDesc="大奖再次来袭！COACH、施华洛世奇、纯银时尚手镯，微趣拍祝您9月好运";
var wxShareTitle="微趣拍幸运抽奖";
WeixinApi.ready(function(Api){
	var wxData={
		imgUrl: wxShareCover,
		link: wxShareLink,
		desc: wxShareDesc,
		title: wxShareTitle
	};

	var wxCallbacks={
		ready: function(){
			//alert('准备分享');
		},
		cancel: function(){
			alert('分享了才能领取奖品哦~');
		},
		fail:function(){
			alert('讨厌，分享失败了，再来一次嘛');
		},
		confirm:function(){
			//alert('分享成功');
			actionShareSuccess();
		},
		all:function(){}
	};

	Api.shareToFriend(wxData, wxCallbacks);
	Api.shareToTimeline(wxData, wxCallbacks);
});
$(document).ready(function(){
	$("#onload_error").css("display", "none");
	$("#onload_success").css("display", "block");
});
</script>
