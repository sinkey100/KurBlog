/**
 * Created by sinke on 2017/6/19.
 */
$(document).ready(function(){
    $(".delete").click(function(){
        var id = $(this).data("id");
        var title = $("#li_"+id+" .title").html();
        if(confirm("您确定要删除 "+title+" 吗？")){
            $.post("/admin/article/api", {
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
});
