/**
 * Created by sinke on 2017/6/21.
 */
$(document).ready(function(){
    $(".alter").click(function(){
        var id          = $(this).data("id");
        var title       = $("#li_"+id+" .title").html();
        var url         = $("#li_"+id+" .url").html();
        var description = $("#li_"+id+" .description").html();
        $("#title").val(title);
        $("#id").val(id);
        $("#url").val(url);
        $("#description").val(description);
        $(".cancel").css({"display":"inline-block"});
        $(".edit h2").html("修改链接");
    });


    $(".delete").click(function(){
        var id = $(this).data("id");
        var title = $("#li_"+id+" .title").html();
        if(confirm("您确定要删除 "+title+" 吗？")){
            $.post("/admin/link/api", {
                'action':'delete',
                'id':id
            }, function(data){
                var data = $.parseJSON(data);
                if(data.err == 0){
                    $("#li_"+id).remove();
                    $(".parent-menu-"+id).remove();
                    alert('删除成功');
                }else{
                    alert('删除失败');
                }
            });
        }else{
            return false;
        }
    });


    $(".cancel").click(function(){
        edit_cancel();

    });
});

function edit_cancel(){
    $("#id").val("0");
    $("#menu-form input:text").val("");
    $(".default-type").click();
    $(".edit h2").html("添加链接");
    $(".cancel").css({"display":"none"});
}