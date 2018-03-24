/**
 * Created by sinkey on 2017/6/18.
 */
var upload_id = '';
$(document).ready(function(){

    $(".status").change(function(){
        var status = $('.status input:radio:checked').val();
        if(status == 2){
            $(".password").css({"display":"block"})
        }else{
            $(".password").css({"display":"none"})
        }
    });

    $(".more").change(function(){
        if($(".more input:checkbox").prop("checked")){
            $("#more").css({"display":"block"});
        }else{
            $("#more").css({"display":"none"});
        }
    });

    $(".draft").click(function(){
        autoSave('btn');
    });

    $(".thumb-upload").click(function(){
        upload_id = 'thumb';
        $("#fileupload").click();
    });

    $(".fa-upload").click(function(){
        upload_id = 'editor';
        $("#fileupload").click();
    });

    $(".thumb-clear").click(function(){
        $(".thumb-img").attr("src","");
        $(".thumb-img").css({"display":"none"});

        $(".thumb-upload").html("上传图片");
        $("#thumb").val("");
        $(this).css({"display":"none"});
    });

    $("#fileupload").wrap("<form id='myupload' action='/admin/base/uploadFile.html' method='post' enctype='multipart/form-data'></form>");

    $("#fileupload").change(function(){
        $("#myupload").ajaxSubmit({
            dataType:  'json',
            beforeSend: function() {
                if(upload_id == 'thumb'){
                    $(".thumb-img").attr("src","/static/admin/img/loading.gif");
                    $(".thumb-img").css({"display":"inline-block"});
                }
            },
            success: function(data) {
                data = $.parseJSON(data);
                if(data.err > 0){
                    alert(data.message);
                    return false;
                }else{
                    var img = data.path;
                    if(upload_id == 'thumb'){
                        $("#thumb").val(img)
                        $(".thumb-img").attr("src",img);
                        $(".thumb-img").css({"display":"inline-block"});
                        $(".thumb-upload").html("更改图片");
                        $(".thumb-clear").css({"display":"inline-block"});
                    }else if(upload_id=='editor'){
                        simplemde.codemirror.replaceSelection("![]("+ img +")");
                    }
                }
            },
            error:function(xhr){
                alert(xhr.responseText)
            }
        });
    });
});


function autoSave(from){
    //id content title
    var action  = $("#controller").val();
    var id      = $("#id").val();
    var title   = $("#title").val();
    var content = simplemde.value();
    if(content.length > 0){
        $.post("/admin/"+action+"/api", {
            'action'    :'autoSave',
            'id'        :id,
            'title'     :title,
            'content'   :content
        }, function(data){
            var data = $.parseJSON(data);
            if(data.err == 0){
                $("#id").val(data.id);
                if(from ==  'btn'){
                    alert('自动保存成功');
                }
            }
        });
    }
}