<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>微趣拍-转盘抽奖</title>
<style type="text/css">
.container{width: 300px; height:300px;border:1px solid transparent;position: relative;margin:10px auto;}
#Turntable{border-radius: 200px;width:300px; height:300px; background:url(images_table/ly-plate.png) no-repeat;background-size: contain;}
#btn{border:0px;position:absolute; top: 80px; left: 40px;bottom: 0px;right: 0px; width:120px;margin:auto;background:url('images_table/start.png') no-repeat;background-size: auto 110px;}

</style>
<script type="text/javascript">
var token = "<?php echo $token ?>";

$(function(){
  var score;

    $("#btn").click(function(){
        //避免重复发请求扣积分
        $('#btn').attr('disabled',true);
        $.ajax({
            type: "POST",
            cache: false,
            //url:'<?php echo $this->createUrl('test');?>',
            url: 'index.php?r=turntable/main&token=' + token,
            dataType: "json",
            success: function(data) {       
                if(data.err==1){
                  return top.postMessage('login','*');
                }
               if(data.err==2){
                  alert("您的积分不够");
                  return false;
                }
                //随机赋值
                //实时更新积分
                score=data.user_score;
                if(data.award_id==1){
                    turntable(1,315,'恭喜您抽中双倍卡');
                }
                if(data.award_id==2){
                    turntable(2,45,'恭喜您抽中延迟卡');
                }
                if(data.award_id==3){
                    turntable(3,90,'恭喜您抽中5元拍券');
                }
                if(data.award_id==4){
                    turntable(4,135,'恭喜您抽中30积分');
                }
                if(data.award_id==5){
                    turntable(5,180,'恭喜您抽中10积分');
                }
                if(data.award_id==6){
                    turntable(6,225,'恭喜您抽中5积分');
                }
                if(data.award_id==7){
                    turntable(7,270,'恭喜您抽中1积分');
                }
                if(data.award_id==8 || data.award_id==0){
                   turntable(8,360,'很遗憾，这次您未抽中奖');
                }
              
            }

        });
        

    })
    var turntable=function(id,num,text){
        $('#Turntable').stopRotate();
        $("#Turntable").rotate({
            duration:3000,
            angle: 0,
            animateTo:1440+num,
            easing: $.easing.easeOutSine,
            callback: function(){
             $('label.score').html("").html(score);   
             alert(text);                
             $('#btn').attr('disabled',false);
            }
        });
    }
});
</script>
</head>

<body>
<div class="container">
    <div id="Turntable"></div>
    <input type="button" id="btn" />
<!--     <div id="btn"><img src="images_table/start.png" id="startbtn" width="90" /></div> -->
</div>
</body>
</html>