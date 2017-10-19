let $APP = getApp();
let $wxaCMS = $APP.util.extend(true,{},$APP.iCMS);

$wxaCMS.data.userInfo = {};
$wxaCMS.data.hasUserInfo = false;

$wxaCMS.onLoad = function () {
    if ($APP.globalData.userInfo) {
        this.setData({
            userInfo: $APP.globalData.userInfo,
            hasUserInfo: true
        })
    } else if (this.data.canIUse) {
        // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
        // 所以此处加入 callback 以防止这种情况
        $APP.userInfoReadyCallback = res => {
            this.setData({
                userInfo: res.userInfo,
                hasUserInfo: true
            })
        }
    } else {
        // 在没有 open-type=getUserInfo 版本的兼容处理
        wx.getUserInfo({
            success: res => {
                $APP.globalData.userInfo = res.userInfo
                this.setData({
                    userInfo: res.userInfo,
                    hasUserInfo: true
                })
            }
        })
    }
    this.setData({
        site: $APP.globalData.site
    });
}
$wxaCMS.myArticleTap = function () {
    $APP.iCMS.alert("开发中...");
}
$wxaCMS.myFavoriteTap = function () {
    $APP.iCMS.alert("开发中...");
}
$wxaCMS.aboutTap = function () {
    $APP.iCMS.alert(
        "iCMS 是一套采用 PHP 和 MySQL 构建的高效简洁的内容管理系统,为您的应用提供一个完美的开源解决方案",
        null,
        '关于iCMS'
    );
}

$wxaCMS.run();
