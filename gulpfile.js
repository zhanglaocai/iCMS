/**
 * 初始化
 * npm install gulp-jshint gulp-util gulp-imagemin gulp-sass gulp-minify-css gulp-uglify gulp-rename gulp-concat gulp-clean gulp-clean tiny-lr --save-dev
 */

// 引入 gulp及组件
var gulp    = require('gulp'),                 //基础库
    imagemin = require('gulp-imagemin'),       //图片压缩
    sass = require('gulp-ruby-sass'),          //sass
    minifycss = require('gulp-minify-css'),    //css压缩
    autoprefixer = require('gulp-autoprefixer'),
    // jshint = require('gulp-jshint'),           //js检查
    uglify  = require('gulp-uglify'),          //js压缩
    rename = require('gulp-rename'),           //重命名
    concat  = require('gulp-concat'),          //合并文件
    clean = require('gulp-clean'),             //清空文件夹
    tinylr = require('tiny-lr'),               //livereload
    server = tinylr(),
    port = 35729,
    livereload = require('gulp-livereload');   //livereload

// HTML处理
gulp.task('html', function() {
    var htmlSrc = './template/www/*.htm',
        htmlDst = './template/www/';

    gulp.src(htmlSrc)
        .pipe(livereload(server))
        .pipe(gulp.dest(htmlDst))
});

// 样式处理
gulp.task('css', function () {
    var cssSrc = './template/www/static/ui.css',
        cssDst = './template/www/static/';

    gulp.src(cssSrc)
        // .pipe(sass({ style: 'expanded'}))
        // .pipe(sass({ style: 'compressed' }))
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
        .pipe(gulp.dest(cssDst))
        .pipe(rename({ suffix: '.min' }))
        .pipe(minifycss())
        .pipe(livereload(server))
        .pipe(gulp.dest(cssDst));
});

// 图片处理
gulp.task('images', function(){
    var imgSrc = './template/wwwsrc/static/img/*',
        imgDst = './template/www/static/img/';
    gulp.src(imgSrc)
        .pipe(imagemin())
        .pipe(livereload(server))
        .pipe(gulp.dest(imgDst));


})

// js处理
gulp.task('js', function () {
    var uiSrc = './template/www/static/ui.js',
        uiDst = './template/www/static/',
        uijsSrc = ['./template/www/static/js/*.js','!./template/www/static/js/*.min.js'],
        uijsDst = './template/www/static/js/';

    gulp.src(uiSrc)
        // .pipe(jshint('.jshintrc'))
        //.pipe(jshint.reporter('default'))
        //.pipe(concat('main.js'))
        //.pipe(gulp.dest(jsDst))
        .pipe(rename({ suffix: '.min' }))
        .pipe(uglify())
        // .pipe(concat("main.js"))
        .pipe(gulp.dest(uiDst))
        .pipe(livereload(server));

    gulp.src(uijsSrc)
        .pipe(uglify())
        //.pipe(concat("vendor.js"))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(uijsDst))
        .pipe(livereload(server));

    var appSrc = './public/iCMS.APP.js',
        appDst = './public/',
        appjsSrc = ['./public/js/*.js','!./public/js/*.min.js'],
        appjsDst = './public/js/';

    gulp.src(appSrc)
        .pipe(uglify())
        //.pipe(concat("vendor.js"))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(appDst))
        .pipe(livereload(server));

    gulp.src(appjsSrc)
        .pipe(uglify())
        //.pipe(concat("vendor.js"))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(appjsDst))
        .pipe(livereload(server));

});

// 清空图片、样式、js
gulp.task('clean', function() {
    // gulp.src(['./dist/css', './dist/js/main.js','./dist/js/vendor', './dist/images'], {read: false})
    //     .pipe(clean());
});

// 默认任务 清空图片、样式、js并重建 运行语句 gulp
gulp.task('default', ['clean'], function(){
    gulp.start('html','css','images','js');
});

// 监听任务 运行语句 gulp watch
gulp.task('watch',function(){

    server.listen(port, function(err){
        if (err) {
            return console.log(err);
        }

        // 监听html
        gulp.watch('./template/www/*.htm', function(event){
            gulp.run('html');
        })

        // 监听css
        gulp.watch('./template/www/static/*.css', function(){
            gulp.run('css');
        });

        // 监听images
        gulp.watch('./template/www/static/img/*', function(){
            gulp.run('images');
        });

        // 监听js
        gulp.watch(['./template/www/static/ui.js','./template/www/static/js/*.js'], function(){
            gulp.run('js');
        });

    });
});
