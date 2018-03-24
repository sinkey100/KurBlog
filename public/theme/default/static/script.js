$(document).ready(function() {
    $(window).on("scroll", function() {
        var n = 0;
        var r = $(window).scrollTop();
        r >= n && r > 100 ? ($(".fixed").addClass("hidden")) : $(".fixed").removeClass("hidden"), n = r
    });

    var header = $("#header");
    var form = header.find("form");
    var n = 0;
    var i = !1;
    form.find("button").on("click", function(event) {
        form.find("input").val() || event.preventDefault(), header.hasClass("extend-search") || i || (header.addClass("extend-search"), header.removeClass("extend-menu"), i = !0, form.find("input").focus())
    }), $(window).on("keyup", function($) {
        27 === $.which && form.hasClass("show") && form.find("input").val("").blur()
    }), form.find("input").on("blur", function($) {
        !form.find("input").val() && header.hasClass("extend-search") && (event.preventDefault(), header.removeClass("extend-search"), setTimeout(function() {
            i = !1
        }, 300))
    });

    $(".menu-btn").click(function(){
        if($("#nav-list").css("display") == 'block'){
            $("#nav-list").css("display","none");
        }else{
            $("#nav-list").css("display","block");
        }


    });
})