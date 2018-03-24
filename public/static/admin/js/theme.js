/**
 * Created by sinkey on 2017/7/9.
 */
$(document).ready(function(){
    $("body").delegate(".activate","click",function(){
        var id      = $(this).data('id');
        var form    = $('<form></form>');
        form.attr('action', '/admin/setting/activate_theme');
        form.attr('method', 'post');
        form.attr('target', '_self');
        var my_input = $('<input type="text" name="id" />');
        my_input.attr('value', id);
        form.append(my_input);
        form.appendTo(document.body).submit();
        return false;
    });

    $("body").delegate(".delete","click",function(){
        var id      = $(this).data('id');
        var title   = $("#theme_"+id+" .info .title").html();
        if(confirm("您确定要删除主题 "+title+" 吗？\n\n此操作会直接删除主题目录，不可恢复！")){
            var form    = $('<form></form>');
            form.attr('action', '/admin/setting/delete_theme');
            form.attr('method', 'post');
            form.attr('target', '_self');
            var my_input = $('<input type="text" name="id" />');
            my_input.attr('value', id);
            form.append(my_input);
            form.appendTo(document.body).submit();
            return false;
        }else{
            return false;
        }
    });

    $("body").delegate(".closed","click",function(){
        $(".detail").removeClass('show').addClass('hidden');
        $(".detail .info").html('');
    });

    $(".btn-detail").click(function(){
        var id          = $(this).data('id');
        var screenshot  = $("#theme_"+id+" .thumb img").attr('src');
        var title       = $("#theme_"+id+" .info .title").html();
        var version     = $("#theme_"+id+" .info").data("version");
        var author_url  = $("#theme_"+id+" .info").data("author_url");
        var author      = $("#theme_"+id+" .info").data("author");
        var description = $("#theme_"+id+" .info").data("description");

        var html = '<div class="row">' +
            '<div class="col-md-4"><img src="'+ screenshot +'" alt=""></div>' +
            '<div class="col-md-8">' +
            '<h3><small>'+ version +'</small></h3>' +
            '<div class="author">作者：<a href="'+ author_url +'">'+ author +'</a></div>' +
            '<div class="description">'+ description +'</div>' +
            '<div class="btns">';
        if(!$("#theme_"+id).hasClass('active')){
            html += '<button type="button" data-id="'+ id +'" class="btn btn-primary activate">启用</button>' +
                '<button type="button" data-id="'+ id +'" class="btn btn-danger delete">删除</button>';
        }
        html += '<button type="button" class="btn btn-default closed">关闭</button>' +
            '</div></div></div>';
        $(".detail .info").html(html);
        $(".detail").removeClass('hidden').addClass('show');
    });
});