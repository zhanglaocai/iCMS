var iCMS = {
    HOST: 'https://weapp.icmsdev.com/',
    get: function(url, callback) {
        wx.request({
            url: url,
            headers: {
                'Content-Type': 'application/json'
            },
            success(res) {
                if(typeof(callback) === "function") {
                    callback(res);
                }
            }
        })
    }
}
module.exports = {
    API: {
        index: iCMS.HOST + "public/api.php?app=index&device=weapp",
        category: iCMS.HOST + "public/api.php?app=category&device=weapp&tpl=category.index",
        tag: iCMS.HOST + "public/api.php?app=tag&device=weapp&tpl=tag",
        article: iCMS.HOST + "public/api.php?app=article&device=weapp&tpl=article"
    },
    GET: iCMS.get
};
