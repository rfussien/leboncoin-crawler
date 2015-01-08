var gulp = require('gulp'),
    phpunit = require('gulp-phpunit'),
    watch = require('gulp-watch'),
    notify = require('gulp-notify');

gulp.task('default', ['phpunit', 'watch']);

gulp.task('phpunit', function () {
    var options = {
        debug: false,
        clear: true,
        notify: true
    };
    gulp.src('phpunit.xml')
        .pipe(phpunit('', options))
        .on('error', notify.onError({
            title: "Failed Tests!",
            message: "Error(s) occurred during testing..."
        }));
});

gulp.task('watch', function () {
    gulp.watch(['tests/**/*.php', 'src/**/*.php'], ['phpunit']);
});
