var $APP = getApp();
var $wxaCMS = $APP.util.extend({},$APP.iCMS);

$wxaCMS.getArticle = function (id) {
    var that = this;
    $APP.iCMS.GET(
        $APP.iCMS.API.article + '&id=' + id,
        function (json) {
            wx.setNavigationBarTitle({
                title: json.article.title + ' - ' + $APP.globalData.site.name
            });
            that.setData({
                hidden: true,
                article: json.article,
                category: json.category,
                article_list: json.article_list,
                article_prev: json.article_prev,
                article_next: json.article_next
            });
        });
};
$wxaCMS.onLoad = function (options) {
    this.getArticle(options.id);
    this.setData({
        site: $APP.globalData.site
    });
}
$wxaCMS.upTap = function (e) {
    var that = this;
    var iid = e.currentTarget.id,avg_k='a_v_g_'+iid;
    var avg_v = wx.getStorageSync(avg_k)||0;
    var now = Date.now();

    if(now-avg_v<86400){
        $APP.iCMS.alert('您已经点过赞了');
        return;
    }

    $APP.iCMS.POST(
        $APP.iCMS.API.article,
        {
            "action":"vote",
            "type":"good",
            "iid":iid
        },
        function (json) {
            if(json.code){
                wx.setStorageSync(avg_k,now);
                ++that.data.article.good;
                that.setData({
                    article:that.data.article
                });
            }
        }
    );
}
$wxaCMS.favoriteTap = function (e) {
    var that = this;
    var iid = e.currentTarget.id;
    var session = wx.getStorageSync('session_id');
    var param = $APP.util.extend({
        fid:"0",
        uid:session.userid,
        _action:"add"
    },that.data.article.param,session);

    $APP.iCMS.POST(
        $APP.iCMS.API.weixin+'&do=wxapp&method=callback&_app=favorite',param,
        function (json) {
            $APP.iCMS.alert(json.msg);
            if(json.code){
                ++that.data.article.favorite;
                that.setData({
                    article:that.data.article
                });
            }
        }
    );
}
$wxaCMS.run();

