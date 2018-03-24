/**
 * Created by sinkey on 2017/6/20.
 */
$(document).ready(function(){
    $(".delete").click(function(){
        var id = $(this).data("id");
        var title = $("#li_"+id+" .title").html();
        if(confirm("您确定要删除 "+title+" 吗？")){
            $.post("/admin/page/api", {
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
