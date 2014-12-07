<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>微趣拍-转盘抽奖</title>
<style type="text/css">
.container{width:320px;margin:50px auto;position:relative;}
#Turntable{width:300px; height:300px; background:url(images_table/ly-plate.png) no-repeat;background-size: contain;}
#btn{position:absolute; top: 30px; left: 0px;bottom: 0px;right: 19px; width: 120px;width:120px;margin:auto;}
#btn img{cursor:pointer}
</style>
<script type="text/javascript" src="<?php Yii::app()->request->baseUrl;?>assets/Turntable/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php Yii::app()->request->baseUrl;?>assets/Turntable/jQueryRotate.2.2.js"></script>
<script type="text/javascript" src="<?php Yii::app()->request->baseUrl;?>assets/Turntable/jquery.easing.min.js"></script>
<script type="text/javascript">
var token = "<?php echo $token ?>";

$(function(){


    $("#startbtn").click(function(){
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
                if(data.award_id==1){
                    turntable(1,200,'恭喜您抽中双倍卡');
                }
                if(data.award_id==2){
                    turntable(2,110,'恭喜您抽中延迟卡');
                }
                if(data.award_id==3){
                    turntable(3,22,'恭喜您抽中5元拍券');
                }
                if(data.award_id==4){
                    turntable(4,67,'恭喜您抽中30积分');
                }
                if(data.award_id==5){
                    turntable(5,155,'恭喜您抽中10积分');
                }
                if(data.award_id==6){
                    turntable(6,245,'恭喜您抽中5积分');
                }
                if(data.award_id==7){
                    turntable(7,-20,'恭喜您抽中1积分');
                }
                if(data.award_id==8 || data.award_id==0){
                   turntable(8,295,'很遗憾，这次您未抽中奖');
                }
            }
        });
//
       //  var data = [1,2,3,4];
       //  data = data[Math.floor(Math.random()*data.length)];
       // // alert(data);
       //  if(data==1){
       //      turntable(1,200,'恭喜您抽中的一等奖');
       //  }
       //  if(data==2){
       //      turntable(2,110,'恭喜您抽中的二等奖');
       //  }
       //  if(data==3){
       //      turntable(3,-20,'恭喜您抽中的三等奖');
       //  }
       //  if(data==4){
       //      var angle = [22,67,155,245,295];
       //      angle = angle[Math.floor(Math.random()*angle.length)]
       //      turntable(0,angle,'很遗憾，这次您未抽中奖')
       //  }

    })
    var turntable=function(id,num,text){
        $('#Turntable').stopRotate();
        $("#Turntable").rotate({
            duration:3000,
            angle: 0,
            animateTo:1440+num,
            easing: $.easing.easeOutSine,
            callback: function(){
                alert(text);
            }
        });
    }
});
</script>
</head>

<body>
<div class="container">
    <div id="Turntable"></div>
    <div id="btn"><img src="images_table/start.png" id="startbtn" width="120" /></div>
</div>
</body>
</html>