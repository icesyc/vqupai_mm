            <script>
             $(function(){
               //签到限制
                $('.sign_list ul li').find('div.dialog').css('display','block');
                if(<?php echo $days ?>==0){
                  $('.sign_list ul li .dialog').eq(0).css('display','none');
                }else{
                  var a=<?php echo $days ?>;
                  
                  $('.sign_list ul li').each(function(index,obj){
                     if($(this).attr('id')<=a){
                       $(this).find('div.dialog').css('display','none');
                       $(this).find('img').css('display','block');
                       
                     }
                  })
                 $('.sign_list ul li').eq(a).find('div.dialog').css('display','none');
                }

              
               $(".sign_list span:contains(0)").parent().hide();
               $('.sign_list ul li').each(function(index,obj){
                 $(this).find('div.sign_content').click(function(){

                    $.ajax({
                        type: "POST",
                        cache: false,
                        //url:'<?php echo $this->createUrl('test');?>',
                        url: 'index.php?r=signinfo/main&token=' + token,
                       
                        data:{"day":index},
                        dataType: "json",

                        success: function(data) {      
                            if(data.err==1){
                              return top.postMessage('login','*');
                            }
                            if(data.err==2){
                               alert("亲，您今天已经签到了，不能重复签额");
                             return false;
                            }
                            //个人显示信息更新
                            //alert(data.aa);
                            $('.sign_data label.exp').html('').html(data.user_exp);
                            $('.sign_data label.score').html("").html(data.user_score);  
                            $('.sign_list ul li').eq(index).find('img').show();
                            //$('.sign_data label.qiandao').html('').html(data.aa);
                        },
                        error:function(error){ 
                         alert('服务器堵了，请稍后重试'); 
                        } 

                    });

                 });

               });
               
             })
            </script>
            <div class="sign_des"><span style="font-weight: bolder;">签到规则：</span>请点击下方相应白色卡片，每签到一天即可获得相应奖励，亲们，多多签到额</div>
            <div class="sign_list">
                <ul>
                   <?php foreach ($sign_info as $v1): ?>
                    <li id='<?php echo $v1['id']; ?>'>
                        <div class="dialog"></div>
                        <img src="images_table/sign.png" />
                        <div class="sign_title">第<label class="color_e7"><?php echo $v1['day']; ?></label>天</div>
                        <div class="sign_content color_e7">可获得</div>
                        <div class="sign_content">
                            <span class="color_e7">
                            <?php  echo $v1['exp'];?>                                
                           </span>点经验值</div>
                        <div class="sign_content" class="score">
                            <span class="color_e7"><?php  echo $v1['score'];?> </span>积分
                        </div>
                        <div class="sign_content">
                        <span class="color_e7">
                            <?php 
                                 if($v1['coupon']==1003){
                                     print "5";
                                 }else{
                                     print "0";
                                 }
                            ?>
           

                        </span>元拍券</div>
                        <div class="sign_content" class="prop"><span class="color_e7">
                            <?php 
                               if($v1['prop']==1){
                                print "延时卡";
                               }else if($v1['prop']==2){
                                 print "双倍卡";
                               }
                            ?>
                            <?php echo $v1['prop']; ?>

                        </span>
                    </li>
                <?php endforeach ?>
                

                </ul>
            </div>