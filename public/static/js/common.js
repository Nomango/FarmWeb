$(document).ready(function () {
    $("#header_userinfo").hover(function () {
        $("#header_userinfo_list").css('display','block');
    });

    $("#header_userinfo").mouseleave(function () {
        $("#header_userinfo_list").css('display','none');
    });
});
