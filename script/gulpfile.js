var gulp = require('gulp');
var typescript = require('gulp-typescript');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var notify = require("gulp-notify");
var dst = './../app/public/js';
var src = './src';
var ts_option = {target: 'ES5'};

gulp.task('js', function () {
    gulp.src([
        src + '/config.ts'
    ])
        .pipe(typescript(ts_option))
        .js
        .pipe(uglify({preserveComments: 'some'}))
        .pipe(gulp.dest(dst))
        .pipe(notify("Build config.js!"));

    gulp.src([
        src + '/base/*.ts'
    ])
        .pipe(typescript(ts_option))
        .js
        .pipe(uglify({preserveComments: 'some'}))
        .pipe(gulp.dest(dst + '/base'))
        .pipe(notify("Build Base!"));

    gulp.src([
        src + '/services/*.ts'
    ])
        .pipe(typescript(ts_option))
        .js
        .pipe(uglify({preserveComments: 'some'}))
        .pipe(gulp.dest(dst + '/services'))
        .pipe(notify("Build Services!"));

    gulp.src([
        src + '/models/*.ts'
    ])
        .pipe(typescript(ts_option))
        .js
        .pipe(uglify({preserveComments: 'some'}))
        .pipe(gulp.dest(dst + '/models'))
        .pipe(notify("Build Models!"));

    gulp.src([
        src + '/view_models/*.ts'
    ])
        .pipe(typescript(ts_option))
        .js
        .pipe(uglify({preserveComments: 'some'}))
        .pipe(gulp.dest(dst + '/view_models'))
        .pipe(notify("Build ViewModels!"));
});

gulp.task('watch', function () {
    gulp.watch(src + '/**/*.ts', ['js']);
});

gulp.task('lib', function () {
    gulp.src([
        './node_modules/underscore/underscore-min.js'
    ])
        .pipe(concat('lib.js'))
        .pipe(gulp.dest(dst))
        .pipe(notify("Build lib.js!"));
});
