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


});

