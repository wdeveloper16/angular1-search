module.exports = function (grunt) {

	grunt.registerTask('default', ['watch']);

	grunt.initConfig({
		concat: {
			libs: {
				options: {
//                    separator: ';',
					process: function (src, path) {
						var newStr = '// BEGIN ' + path + '\n';

						console.log(path);

						src = src
							.replace(/(^|\n)[ \t]*('use strict'|"use strict");?\s*/g, '$1')
							.replace(/[\n\t\r\s]*$/, '\n');
						newStr += src;
						newStr += '// END ' + path + '\n';

						return newStr;
					}
				},
				src:     [
					'bower_components/lodash/dist/lodash.min.js',
					'bower_components/jquery/dist/jquery.min.js',
					'bower_components/jquery.autoellipsis/src/jquery.autoellipsis.js',
					'bower_components/angular/angular.min.js',
					'bower_components/angular-touch/angular-touch.min.js',
					// 'bower_components/angular-ui-swiper/dist/angular-ui-swiper.min.js',

					'bower_components/imagesloaded/imagesloaded.pkgd.min.js',
					'bower_components/Swiper/dist/js/swiper.min.js',

					'bower_components/smoothTouchScroll/js/*.js',

					'bower_components/jQuery.dotdotdot/src/jquery.dotdotdot.min.js',

					'bower_components/angular-ui-router/release/angular-ui-router.min.js',
					'bower_components/angular-pageslide-directive/dist/angular-pageslide-directive.min.js',
					'bower_components/allmighty-autocomplete/script/autocomplete.js'
					//'bower_components/bootstrap/dist/js/bootstrap.min.js',
					//'public_html/assets/js/jquery-migrate.min.js',
					//'public_html/assets/js/jquery-ui.min.js'

				],
				dest:    'public_html/build/libs.js'
			},
			app:  {
				options: {
//                    separator: ';',
					process: function (src, path) {
						var newStr = '// BEGIN ' + path + '\n';

						console.log(path);

						src = src
							.replace(/(^|\n)[ \t]*('use strict'|"use strict");?\s*/g, '$1')
							.replace(/[\n\t\r\s]*$/, '\n');
						newStr += src;
						newStr += '// END ' + path + '\n';

						return newStr;
					}
				},
				src:     [
					'public_html/assets/js/app/*.js',
					'public_html/assets/js/app/**/*.js'
				],
				dest:    'public_html/build/app.js'
			},
			css:  {
				src:  [
					'bower_components/allmighty-autocomplete/style/autocomplete.css',
					// 'bower_components/angular-ui-swiper/dist/angular-ui-swiper.css',
					'bower_components/Swiper/dist/css/swiper.min.css',
					'bower_components/smoothTouchScroll/css/smoothTouchScroll.css',
					'public_html/build/app.css'
				],
				dest: 'public_html/build/app.css'
			}
		},

		//uglify: {
		//	options: {
		//		mangle: false
		//	},
		//	js:      {
		//		files: {
		//			'public/script.js': ['vendor/Gratheon/Kurapov/assets/js_cache/all.js']
		//		}
		//	}
		//},
		less:   {
			production: {
				//options: {
				//	paths:    ["vendor/Gratheon/Kurapov/assets/css"],
				//	cleancss: false
				//},
				files: {
					'public_html/build/app.css': 'public_html/assets/less/main.less'
				}
			}
		}
	});

	grunt.registerTask('build', function () {
		grunt.task.run('less', 'concat');
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-less');
	//grunt.loadNpmTasks('grunt-contrib-watch');

};
