/**
 * 开发版本的文件导入
 */
(function (){
    var paths  = [
            'require.js',
            'plugin.js',
            'config.js',
            'cookie.js',
            'api.js',
            'artdialog.js',
            'poshytip.js',
            'ui.dialog.js',
            'ui.js',
            'utils.js',
            'user.js',
            'passport.js',
            'common.js',
            'comment.js',
            'icms.js',
        ],
        baseURL = '/public/js/_src/';
    for (var i=0,pi;pi = paths[i++];) {
        document.write('<script type="text/javascript" src="'+ baseURL + pi +'"></script>');
    }
})();
