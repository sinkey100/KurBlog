/**
 * Created by sinke on 2017/6/27.
 */
$(document).ready(function(){
    $(".delete").click(function(){
        var id = $(this).data("id");
        if(confirm("您确定要删除此条评论吗？")){
            $.post("/admin/comment/api", {
                'action':'delete',
                'id':id
            }, function(data){
                var data = $.parseJSON(data);
                if(data.err == 0){
                    $("#li_"+id).remove();
                    alert('删除成功');
                }else{
                    alert('删除失败');
                }
            });
        }else{
            return false;
        }
    });


    $(".edit").click(function(){
        var id = $(this).data('id');
        window.location.href="/admin/comment/edit/"+id;
    })

    $(".u-status-1").click(function(){
        var id = $(this).data("id");
        $.post("/admin/comment/api", {
            'action':'update',
            'id':id,
            'status':1
        }, function(data){
            var data = $.parseJSON(data);
            if(data.err == 0){
                if($("#comment-list").data('status')=='2'){
                    $("#li_"+id).remove();
                }else{
                    $("#li_"+id+" .status span").html('');
                }
                alert('更新成功');
            }else{
                alert('更新失败');
            }
        });

    });

    $(".u-status-0").click(function(){
        var id = $(this).data("id");
        $.post("/admin/comment/api", {
            'action':'update',
            'id':id,
            'status':0
        }, function(data){
            var data = $.parseJSON(data);
            if(data.err == 0){
                if($("#comment-list").data('status')=='1'){
                    $("#li_"+id).remove();
                }else{
                    $("#li_"+id+" .status span").html('待审核');
                }
                alert('更新成功');
            }else{
                alert('更新失败');
            }
        });

    });

});
