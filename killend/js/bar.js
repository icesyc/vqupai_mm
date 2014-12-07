/**
 * Created by apple on 14-10-24.
 */
$(function(){
    $(".btn_help").click(function(){
        $('.dialog').show();
        $('.dialog_dis').eq(0).show();
        $('.dialog_dis img').eq(0).show();
    })
    $('.dialog').click(function(){
        $('.dialog,.dialog_dis').hide();
    })

    $(".dialog_dis img").each(function(index,obj){
        $(this).click(function(){
            if(index!=3){
                $(this).hide();
                $(".dialog_dis img").eq(index+1).show();
            }else{
                $('.dialog,.dialog_dis').hide();
                $(this).hide();
            }
        })
    })
    $(".btn_list").click(function(){
        $('.dialog').show();
        $('.score').show();
    })
    $('.dialog,.score').click(function(){
        $('.dialog,.score').hide();
    })
    $(".btn_create").click(function(){
        $('.dialog').show();
        $('.dialog_tip').show();
    })
    $('.dialog,.dialog_tip').click(function(){
        $('.dialog,.dialog_tip').hide();
    })

    $('.dialog2').css("display","block");
    $('.text_dialog').css("display","block");
    $(".btn3").click(function(){
        $(".dialog2").css("display","none");
        $(".text_dialog").css("display","none");
        return false;
    })
})