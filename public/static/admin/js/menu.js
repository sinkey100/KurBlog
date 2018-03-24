/**
 * Created by sinkey on 2017/6/20.
 */
$(document).ready(function(){
    $(".alter").click(function(){
        var id = $(this).data("id");
        var title = $("#li_"+id+" .title").html();
        var value = $("#li_"+id+" .value").html();
        var parent_id = $("#li_"+id).data('parent_id');
        var type    = $("#li_"+id).data('type');
        $("#title").val(title);
        $("#id").val(id);
        $("#parent_id").val(parent_id);
        $("#value").val(value);
        $("#type-"+type).click();
        $(".cancel").css({"display":"inline-block"});
        $(".edit h2").html("修改菜单");
    });

    $(".add-parent").click(function(){
        edit_cancel();
        var id = $(this).data("id");
        $(".edit h2").html("添加子菜单");
        $("#parent_id").val(id);
        $(".cancel").css({"display":"inline-block"});
    });

    $(".delete").click(function(){
        var id = $(this).data("id");
        var title = $("#li_"+id+" .title").html();
        if(confirm("该菜单下的子菜单将会一起删除！\n\n您确定要删除 "+title+" 吗？")){
            $.post("/admin/menu/api", {
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
    $("#parent_id").val("0");
    $("#menu-form input:text").val("");
    $(".default-type").click();
    $(".edit h2").html("添加菜单");
    $(".cancel").css({"display":"none"});
}