/**
 * 血战wap版的js交互 by icesyc
 */
$.fn.toast = function(msg, callback){
    callback = callback || function(){};
    this.text(msg);
    var self = this;
    this.fadeIn(300, function(){
        if(this.fadeTimer){
            clearTimeout(this.fadeTimer);
        }
        this.fadeTimer = setTimeout(function(){
            self.fadeOut(300, callback);
        }, 1000);
    });
};

$.fn.popup = function(){
    var $me = $(this).show();
    var popw = $me.width();
    var poph = $me.height();
    var winh = $(window).height();
    var winw = $(window).width();
    var l = document.body.scrollLeft + (winw - popw) / 2 + 'px';
    var h = document.body.scrollTop + (winh - poph) / 2 + 'px';
    $('<div class="mask"></div>').appendTo(document.body);
    $('.mask').height(document.body.scrollHeight);
    $('.mask').on('click', function(){
        $me.hide();
        $('.mask').remove();
    });
    $me.css('left', l).css('top', h);
};
//simple template
$.fn.render = function(data){
    var html = this.html().split(/\{|\}/);
    for (var i = 0; i < html.length; i++) {
        var v = html[i];
        if (v in data) {
            html[i] = data[v];
        }
    }
    return this.html(html.join(''));
};

$(function(){
    //创建血战
    $('.j-create').on('click', function(e){
        e.preventDefault();
        var data = {};
        data.pool_id = $(this).data('pool-id');
        var url = "index.php?r=killEnd/create";
        $.post(url, data, function(rsp){
            if(rsp.success){
                location.href = 'index.php?r=killEnd/view&id=' + rsp.id;
                return true;
            }
            //积分不够
            if(rsp.code == 1100){
                $('.dialog').render(rsp).popup();
            }else{
                $('#msg_tip').toast(rsp.msg);
            }
        }, 'json');
    });
});