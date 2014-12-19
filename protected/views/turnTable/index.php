<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>微趣拍-转盘抽奖</title>
<style type="text/css">
.container{width: 225px; height:225px;border:1px solid transparent;position: relative;margin:25px auto;border-radius: 200px;}
#Turntable{margin-bottom:30px;border-radius: 200px;width:225px; height:225px; background:url(images_table/ly-plate.png) no-repeat;background-size: contain;}
#btn{border:0px;position:absolute; top: 0px; left: 0px;bottom: 0px;right: 0px; width:60px;height:85px;margin:auto;background:url('images_table/start.png') no-repeat;background-size: contain;}

</style>
<script type="text/javascript">
  var token = "<?php echo $token ?>";
  var uid = "<?php echo $uid ?>";
  var iscount="<?php echo $isone ?>";
  //抽奖提示
  function tips(){
    $('.dialog1').show();
    $('.dialog_content').show();
  }
  function ajaxurl(){
    $('#btn').attr('disabled',true);

        $.ajax({
            type: "POST",
            cache: false,
            url: 'index.php?r=turnTable/main&token=' + token,
            data:{"uid":uid},
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
                    turntable(2,45,'恭喜您抽中延时卡');
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
                iscount=1;
            }

        });
  }

  function turntable(id,num,text){
    $('#Turntable').stopRotate();
    $("#Turntable").rotate({
                duration:3000,
                angle: 0,
                animateTo:1440+num,
                easing: $.easing.easeOutSine,
                callback: function(){
                  
                 alert(text);   
                 $('label.score').html("").html(score);             
                 $('#btn').attr('disabled',false);
                }
    });
  }


$(function(){
  
 //抽奖提示操作
        $('#btn').click(function(){
            
            $('#btn').attr('disabled',true);
            if(iscount==0){
              ajaxurl();
            
            }else{
              tips();
            }
      
            // $.ajax({
            //     type: "POST",
            //     cache: false,
            //     url: 'index.php?r=turnTable/isone&token=' + token,
            //      data:{"uid":uid},
            //     dataType: "json",
            //     success: function(data) { 
            //    // alert(data.err);     
            //         if(data.err==0){
            //           ajaxurl();
            //         }else{
            //             tips();
            //         }
            //     }   

            // });   
        }); 
       $('.btn_dis').click(function(){
          $('.dialog1').hide();
          $('.dialog_content').hide();
          $('#btn').attr('disabled',false);
       })       
       $('.btn_go').click(function(){
          $('.dialog1').hide();
          $('.dialog_content').hide();
          var aa=$('label.score').html();
          var bb=parseInt(aa);
          var cc=bb-10;
          if(cc<0){
            $('label.score').html('').html(bb);
            alert('您的积分不够啦！');
            $('#btn').attr('disabled',false);
          }else{
            $('label.score').html('').html(cc);
            ajaxurl();   
          }
                 
        })
       
        
});
</script>
</head>

<body>
 <div class="sign_des"><span style="font-weight: bolder;">抽奖规则：</span>1、每天第一次抽奖不消耗积分;2、每天第二次抽奖开始，每次消耗10积分</div>
<div class="container">
    <div id="Turntable"></div>
    <input type="button" id="btn" />
<!--     <div id="btn"><img src="images_table/start.png" id="startbtn" width="90" /></div> -->
</div>
</body>
</html>