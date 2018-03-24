/**
 * Created by nobita on 11/11.
 */
/**
 * Created by sinke on 2017/6/26.
 */
$(document).ready(function(){
	$('.comment-reply-link').text('@回复');
	var user=getCookie("username");
    var arr = user.split(',');
    if (user!=""){
        $("#comment #author").val(arr[0]);
        $("#comment #email").val(arr[1]);
        $("#comment #url").val(arr[2]);
        $(".comment-form-info").css("display","none");

    }
    else {
        $("#comment #author").val('游客');
        $("#comment #url").val('http://');
    }
    $("#comment").delegate(".comment-reply-link","click",function(){
        var id = $(this).data("id");
        var author = $(this).data("author");
        $("#parent_id").val(id);
        $(".send-comment h4").html('回复给'+author);
    });

    $("#comment-submit").click(function(){
        var parent_id = $("#parent_id").val();
        var type      = $("#comment .type").val();
        var value     = $("#comment .value").val();
        var author    = $("#comment #author").val();
        var email     = $("#comment #email").val();
        var url       = $("#comment #url").val();
        var content   = $("#comment-content").val();
		setCookie("username",author+','+email+','+url,300);
        $.post("/comment/api", {
            'action'    :'post',
            'parent_id' :parent_id,
            'type'      :type,
            'value'     :value,
            'author'    :author,
            'email'     :email,
            'url'       :url,
            'content'   :content
        }, function(data){
            var data = $.parseJSON(data);
            if(data.err == 0){
                if(parent_id > 0){
                    $("#comment-"+parent_id).append(data.html);
                }else{
                    $(".comment-list").append(data.html);
                }
                cancel_comment();
                if(data.status == 0){
                    alert('提交成功，您的评论可能需要在审核后才能显示。');
                }else{
					$('.wantcom').html('「人生在世，装逼成功」')
                    //alert('评论成功');
                }
            }else{
				$('.wantcom').html('「人生在世，装逼失败」')
                //alert('评论失败');
            }
        });
        return false;
    });

    $(".comment-cancel").click(function(){
        cancel_comment();
    });


});

function cancel_comment(){
    $(".comment-cancel").css({"display":"none"});
    $(".comment-cancel").html('');
    $('#comment #parent_id').val('0');
    //$('#author').val('');
    //$('#email').val('');
    //$('#url').val('');
    $('#comment-content').val('');
}

function setCookie(cname,cvalue,exdays){
    var d = new Date();
    d.setTime(d.getTime()+(exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname+"="+cvalue+"; "+expires;
    console.log(document.cookie);
}

function getCookie(cname){
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name)==0) return c.substring(name.length,c.length);
    }
    return "";
}