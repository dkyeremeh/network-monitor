"use strict";

module.exports = function ( grunt ){
	
	require('load-grunt-tasks')( grunt ); 

	var config = {

		cssmin: {
			build:{
				files: {
					"app/static/css/min.css": [

						"app/static/css/jquery-ui.css",
						"app/static/css/bootstrap.css",

						"app/static/css/raleway.css",
						"app/static/css/ionicons.min.css",

						"app/static/css/angular.datepicker.css",

						"app/static/css/dekyfin-ui.css",

						"app/static/css/style.css",
						"app/static/css/admin.css",

						"app/static/css/handheld.css",
						"app/static/css/mobile.css",
					],
				},
			}
		},

		uglify: {
			build:{
				options: {
					mangle: false,
					compress: false,
				},
				files: {
					'app/static/js/min.js': [

						"app/static/js/config.js",
						"app/static/js/modernizr.min.js",
						"app/static/js/functions.js",
						"app/static/js/jquery.min.js",
						"app/static/js/jquery-ui.min.js",
						"app/static/js/bootstrap.min.js",

						"app/static/js/angular/angular.js",

						"app/static/js/angular/angular-ui-router.min.js",
						"app/static/js/angular/angular-filter.min.js",
						"app/static/js/angular/angular-sanitize.min.js",
						"app/static/js/angular.datepicker.js",
						"app/static/js/dekyfin-ui.js",

						"app/static/js/myshop.ng.js",
						"app/static/js/myshop.controllers.js",
						"app/static/js/myshop.services.js",
						"app/static/js/myshop.directives.js",
						"app/static/js/myshop.filters.js",

						"app/static/js/StorageData.js",
						"app/static/js/main.js",
					]
				}
			}
		},

		// babel: {
		// 	build:{
		// 		files: {
		// 			"app/static/css/min.css": [
		// 				"app/static/css/min.es6.css"
		// 			],
		// 		},
		// 	}
		// },

		watch: {
			css: {
				files: [ 
					'app/static/css/*.css',
					"!app/static/css/min.css"
				],
				tasks: [ 'css' ]
			},

			js: {
				files: [ 
					'app/static/js/*.js',
					"!app/static/js/min.js",
				],
				tasks: [ 'js' ]
			},

			gruntFile: {
				files: 'Gruntfile.js',
				tasks: [ "css", "js" ]
			},
		}

	}

	// configure the tasks
	grunt.initConfig( config );

	// // load the tasks
	// grunt.loadNpmTasks('grunt-contrib-cssmin');
	// grunt.loadNpmTasks('grunt-contrib-uglify');
	// grunt.loadNpmTasks('grunt-contrib-watch');
	// grunt.loadNpmTasks('grunt-babel');

	// define the tasks
	grunt.registerTask( 'css', 'Compiles the stylesheets.', [ 'cssmin' ] );
	grunt.registerTask( 'js', 'Compiles the JavaScript files.', [ "uglify"] );
}