/**
 * Created by sinke on 2017/6/26.
 */
$(document).ready(function(){
    $("#comment").delegate(".comment-reply-link","click",function(){
        var id = $(this).data("id");
        var author = $(this).data("author");
        $("#parent_id").val(id);
        $(".comment-cancel").html('取消回复给'+author);
        $(".comment-cancel").css({"display":"inline-block"});
    });

    $("#comment-submit").click(function(){
        var parent_id = $("#parent_id").val();
        var type      = $("#comment .type").val();
        var value     = $("#comment .value").val();
        var author    = $("#comment #author").val();
        var email     = $("#comment #email").val();
        var url       = $("#comment #url").val();
        var content   = $("#comment-content").val();
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
                    alert('评论成功');
                }
            }else{
                alert('评论失败');
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
    $('#author').val('');
    $('#email').val('');
    $('#url').val('');
    $('#comment-content').val('');


}