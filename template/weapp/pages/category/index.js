var $APP = getApp();
var $wxaCMS = $APP.util.extend({},$APP.iCMS);

$wxaCMS.data.cid = 0;
$wxaCMS.data.article_list_loading = false;

$wxaCMS.getData = function () {
    this.setData({
        loading: false
    });
    var that = this;
    $APP.iCMS.GET(
        $APP.iCMS.API.category_index,
        function (json) {
            that.setData({
                loading: true,
                hidden: true,
                tag_list: json.tag_list,
                category_list: json.category_list,
                cid: json.category_list[0].cid
            });
            that.getList(json.category_list[0].cid);
        }
    );
},
$wxaCMS.getList = function (cid) {
    if (this.data.pageLast) return;
    this.setData({
        article_list_loading: false
    });
    var cid = cid||this.data.cid;
    var that = this;
    $APP.iCMS.GET(
        $APP.iCMS.API.category + '&cid=' + cid + '&page=' + this.data.pageNum,
        function (json) {
            that.setData({
                article_list_loading: true,
                article_list: json.article_list
            });
        });
};
$wxaCMS.onLoad = function () {
    this.getData();
}
$wxaCMS.tabClick = function (e) {
    this.setData({
        cid: e.currentTarget.id
    });
    this.getList();
}
$wxaCMS.run();
