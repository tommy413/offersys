'use strict';

var gulp = require('gulp');
var bower = require('gulp-bower');
var sass = require('gulp-sass')
var composer = require('gulp-composer');

gulp.task('bower', function() {
	return bower('./public/lib/');
});

gulp.task('composer', function() {
	return composer({
		bin: 'composer'
	});
});

gulp.task('default', ['bower', 'composer']);
// gulp.task('default', ['sass', 'sass:watch']);
