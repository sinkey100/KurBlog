/**
 * Created by sinkey on 2017/6/17.
 */
$(document).ready(function(){
    $(".alter").click(function(){
        var id = $(this).data("id");
        var title = $("#li_"+id+" .title").html();
        var slug = $("#li_"+id+" .slug").html();
        $("#category_id").val(id);
        $(".edit h2").html("修改分类");
        $(".cancel").css({"display":"inline-block"});
        $("#title").val(title);
        $("#slug").val(slug);
    });

    $(".delete").click(function(){
        var id = $(this).data("id");
        var title = $("#li_"+id+" .title").html();
        if(confirm("您确定要删除 "+title+" 吗？")){
            $.post("/admin/category/api", {
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

    $(".submit").click(function(){
        var id = $("#category_id").val();
        var title = $.trim($("#title").val());
        var slug = $("#slug").val();
        var reg = /^[0-9a-zA-Z\-]*$/g;
        if(!reg.test(slug) || slug.length == 0){
            alert('别名格式不正确，请检查。');
            return false;
        }else if(title.length == 0) {
            alert('分类名称不可为空。');
            return false;
        }
        if(id == 0){
            return true;
        }else{
            $.post("/admin/category/api", {
                'action':'update',
                'id':id,
                'title':title,
                'slug':slug
            }, function(data){
                var data = $.parseJSON(data);
                if(data.err == 0){
                    $("#li_"+id+" .title").html(title);
                    $("#li_"+id+" .slug").html(slug);
                    edit_cancel();
                    alert('修改成功');
                }else{
                    alert('修改失败');
                }
            });
        }
        return false;
    });

    $(".cancel").click(function(){
        edit_cancel();

    });
});

function edit_cancel(){
    $("#category_id").val("0");
    $("#title").val("");
    $("#slug").val("");
    $(".edit h2").html("添加分类");
    $(".cancel").css({"display":"none"});
}