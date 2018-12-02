/**
 * Created by sinkey on 2017/6/21.
 */
$(document).ready(function(){

    $(".home").change(function(){
        var type = $('.home input:radio:checked').val();
        if(type == 'article'){
            $(".value-id").css({"display":"none"});
        }else{
            if(type == 'category'){
                $(".value-id label").html('分类ID');
            }else if(type=='page'){
                $(".value-id label").html('页面ID');
            }
            $(".value-id").css({"display":"block"});

        }
    });

    $("#seo-tag-menu li").click(function(){
        var id = $(this).data('id');
        $('#seo-tag-menu li').removeClass('active')
        $('#seo-tag-menu .'+id).addClass('active')
        $('.seo-tag-list .item').css({
            'display' : 'none'
        });
        $('.form-seo .form-group').css({
            'display' : 'none'
        });
        $(".seo-tag-list ."+id).css({
            'display' : 'table-row'
        });
        $(".form-seo .form-group."+id).css({
            'display' : 'block'
        })

    })


});

