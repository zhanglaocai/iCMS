var iCMS = require('./utils/iCMS.js');
var util = require('./utils/util.js');

App({
    iCMS: iCMS,
    util: util,
    globalData: {
        userInfo: null,
        site: null
    },
    onLaunch: function () {
        this.getUserInfo();
        // //调用API从本地缓存中获取数据
        // // var logs = wx.getStorageSync('logs') || []
        // // logs.unshift(Date.now())
        // // wx.setStorageSync('logs', logs)
        var that = this;
        iCMS.GET(
            iCMS.API.site,
            function (json) {
                wx.setNavigationBarTitle({
                    title: json.title + ' - ' + json.seotitle
                });
                that.globalData.site = json;
            }
        );
    },
    userInfoReadyCallback: function (info) {
        console.log(info);
        iCMS.POST(
            iCMS.API.weixin+'&do=wxapp&method=session',info,
            function (json) {
                wx.setStorageSync('session_id',json);
            }
        );
    },
    getUserInfo: function () {
        var that = this
        // console.log(this.globalData.userInfo);
        // if (this.globalData.userInfo) {
        //     if (this.userInfoReadyCallback) {
        //         this.userInfoReadyCallback(this.globalData.userInfo)
        //     }
        // } else {
            //调用登录接口
            wx.login({
                success: function (res) {
                    wx.getUserInfo({
                        success: function (info) {
                            that.globalData.userInfo = info.userInfo
                            info.loginCode = res.code;
                            if (that.userInfoReadyCallback) {
                                that.userInfoReadyCallback(info)
                            }
                        }
                    })
                }
            })
        // }
    }
})
