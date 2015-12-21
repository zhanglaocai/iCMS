$(function(){
    $("img.lazy").lazyload();
    $(".carousel-box").slider({
        left_btn:'#carousel-left',
        right_btn:'#carousel-right',
    });
    $(".tabs-wrap").tabs({action:'mouseover'});
    $(".rank").tabs({item:'.rank-list',action:'mouseover'});

    $(".search-btn").click(function(event) {
        event.preventDefault();
        var q = $('[name="q"]',"#search-form").val();
        if(q==""){
            iCMS.alert("请输入关键词");
            return false;
        }
    });
})
