    <div class="container">
<section>&nbsp;</section>
<section class="content" id="vertical">
   <ul>
       <li><a href="#"><img src="<?php echo $this->_sourceUri; ?>images/img1.png" width="68"></a><div class="info"></div></li>
       <li class="m_l6px"><a href="#"><img src="<?php echo $this->_sourceUri; ?>images/img2.png" width="68"></a><div class="info"></div></li>
       <li class="m_l6px"><a href="#"><img src="<?php echo $this->_sourceUri; ?>images/img3.png" width="68"></a><div class="info"></div></li>
       <li class="m_t6px"><a href="#"><img src="<?php echo $this->_sourceUri; ?>images/img4.png" width="68"></a><div class="info"></div></li>
       <li class="m_l6px m_t6px"><a href="#"><img src="<?php echo $this->_sourceUri; ?>images/img5.png" width="68"></a><div class="info"></div></li>
       <li class="m_l6px m_t6px"><a href="#"><img src="<?php echo $this->_sourceUri; ?>images/img6.png" width="68"></a><div class="info"></div></li>
       <li class="m_t6px"><a href="#"><img src="<?php echo $this->_sourceUri; ?>images/img7.png" width="68"></a><div class="info"></div></li>
       <li class="m_l6px m_t6px"><a href="#"><img src="<?php echo $this->_sourceUri; ?>images/img8.png" width="68"></a><div class="info"></div></li>
       <li class="m_l6px m_t6px"><a href="#"><img src="<?php echo $this->_sourceUri; ?>images/img9.png" width="68"></a><div class="info"></div></li>
   </ul>
</section>
	</div>

<script>
    $(function(){
        $(".info").show(3000);
        setTimeout(function(){$("#vertical a").hide(1000);},2000);
        setTimeout(function(){
            $(".content ul li").eq(0).animate({top:"74px"},"slow");
            $(".content ul li").eq(6).animate({top:"-74px"},"slow");
            $(".content ul li").eq(2).animate({top:"74px"},"slow");
            $(".content ul li").eq(8).animate({top:"-74px"},"slow");
            $(".content ul li").eq(0).animate({left:"74px"},"slow");
            $(".content ul li").eq(6).animate({left:"74px"},"slow");
            $(".content ul li").eq(3).animate({left:"74px"},"slow");
            $(".content ul li").eq(5).animate({left:"-74px"},"slow");
            $(".content ul li").eq(2).animate({left:"-74px"},"slow");
            $(".content ul li").eq(8).animate({left:"-74px"},"slow");
            $(".content ul li").eq(1).animate({top:"74px"},"slow");
            $(".content ul li").eq(7).animate({top:"-74px"},"slow");

            $(".content ul li").eq(2).animate({left:"-148px"},"slow");
            $(".content ul li").eq(4).animate({left:"-74px"},"slow");
            $(".content ul li").eq(8).animate({left:"-148px"},"slow");

            $(".content ul li").eq(0).animate({left:"148px"},"slow");
            $(".content ul li").eq(6).animate({left:"148px"},"slow");
            $(".content ul li").eq(3).animate({left:"148px"},"slow");

            $(".content ul li").eq(8).animate({top:"-148px"},"slow");
            $(".content ul li").eq(5).animate({top:"-74px"},"slow");
            $(".content ul li").eq(6).animate({top:"-74px"},"slow");
            $(".content ul li").eq(0).animate({top:"0px"},"slow");

            $(".content ul li").eq(3).animate({top:"74px"},"slow");
            $(".content ul li").eq(1).animate({top:"148px"},"slow");
            $(".content ul li").eq(2).animate({top:"148px"},"slow");


        },3000);

        var turn = function(target,time,opts){
            target.find('li').click(function(){
                $(this).find('a').stop().animate(opts[0],time,function(){
                    $(this).hide().next().show();
                    $(this).next().animate(opts[1],time);
                });
				///*
            },function(){
				var awardId=0;
				$.ajaxSetup({async: false});
				$.get("<?php echo Yii::app()->createUrl('actBaidu/requestAward', array('token'=>$token)); ?>",function(data,status,datatype){ awardId=data; });
				console.log(awardId);
				//$(this).find("a").find("img").attr("src", "<?php echo $this->_imagesUri; ?>img"+awardId+".png");
				$(this).find("a").find("img").attr("src", "<?php echo $this->_imagesUri; ?>img9.png");
                $(this).find('.info').animate(opts[0],time,function(){
                    $(this).hide().prev().show();
                    $(this).prev().animate(opts[1],time);
                });
                setTimeout(function(){ window.location.href="<?php echo Yii::app()->createUrl('actBaidu/success', array('token'=>$token)); ?>";},1500);
				//*/
            });
        }
        var verticalOpts = [{'width':0},{'width':'68px'}];
        turn($('#vertical'),300,verticalOpts);
    })
</script>
