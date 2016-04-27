var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var notify = require("gulp-notify");

var dst = './../app/public';
var src = './src';

gulp.task('js', function () {
    gulp.src([
        src + '/config.js',
        src + '/base/*.js',
        src + '/services/*.js',
        src + '/models/**/*.js',
        src + '/view_models/*.js'
    ])
        .pipe(concat('script.js'))
        .pipe(uglify({preserveComments: 'some'}))
        .pipe(gulp.dest(dst + '/js/'))
        .pipe(notify("Build Script!"));
});

gulp.task('watch', function () {
    gulp.watch(src + '/**/*.js', ['js']);
});

gulp.task('lib', function () {
    gulp.src([
        './node_modules/jquery/dist/jquery.min.js',
        './node_modules/moment/min/moment.min.js',
        './node_modules/underscore/underscore-min.js',
        './node_modules/knockout/build/output/knockout-latest.js'
    ])
        .pipe(concat('lib.js'))
        .pipe(gulp.dest(dst + '/js/'))
        .pipe(notify("Build Lib!"));
});