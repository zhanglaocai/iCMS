$(function() {
    $("img.lazy").lazyload();
    $(".carousel-box").slider({
        left_btn: '#carousel-left',
        right_btn: '#carousel-right',
    });
    $(".tabs-wrap").tabs({
        action: 'mouseover'
    });
    $(".rank").tabs({
        item: '.rank-list',
        action: 'mouseover'
    });

    // $(".search-btn").click(function(event) {
    //     event.preventDefault();
    //     var q = $('[name="q"]',"#search-form").val();
    //     if(q==""){
    //         iCMS.alert("请输入关键词");
    //         return false;
    //     }
    // });
})
// function hover (a, t, l) {
//     var pop,timeOutID = null,t = t || 0, l = l || 0;
//     a.hover(function() {
//         pop = $(".popover",$(this).parent());
//         $(".popover").hide();
//         var position = $(this).position();
//         pop.show().css({
//             top: position.top + t,
//             left: position.left + l
//         }).hover(function() {
//             window.clearTimeout(timeOutID);
//             $(this).show();
//         }, function() {
//             $(this).hide();
//         });
//         window.clearTimeout(timeOutID);
//     }, function() {
//         timeOutID = window.setTimeout(function() {
//             pop.hide();
//         }, 2500);
//     });
// }
