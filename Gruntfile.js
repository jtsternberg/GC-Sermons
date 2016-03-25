module.exports = function( grunt ) {

	require('load-grunt-tasks')(grunt);

	var pkg = grunt.file.readJSON( 'package.json' );

	var bannerTemplate = '/**\n' +
		' * <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
		' * <%= pkg.author.url %>\n' +
		' *\n' +
		' * Copyright (c) <%= grunt.template.today("yyyy") %>;\n' +
		' * Licensed GPLv2+\n' +
		' */\n';

	var compactBannerTemplate = '/** ' +
		'<%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %> | <%= pkg.author.url %> | Copyright (c) <%= grunt.template.today("yyyy") %>; | Licensed GPLv2+' +
		' **/\n';

	// Project configuration
	grunt.initConfig( {

		pkg: pkg,


		watch:  {
			styles: {
				files: ['assets/**/*.css','assets/**/*.scss'],
				tasks: ['styles'],
				options: {
					spawn: false,
					livereload: true,
					debounceDelay: 500
				}
			},
			scripts: {
				files: ['assets/**/*.js'],
				tasks: ['scripts'],
				options: {
					spawn: false,
					livereload: true,
					debounceDelay: 500
				}
			},
			php: {
				files: ['**/*.php', '!vendor/**.*.php'],
				tasks: ['php'],
				options: {
					spawn: false,
					debounceDelay: 500
				}
			}
		},

		makepot: {
			dist: {
				options: {
					domainPath: '/languages/',
					potFilename: pkg.name + '.pot',
					type: 'wp-plugin'
				}
			}
		},

		addtextdomain: {
			dist: {
				options: {
					textdomain: pkg.name
				},
				target: {
					files: {
						src: ['**/*.php']
					}
				}
			}
		},

		githooks: {
			all: {
				// create zip and deploy changes to ftp
				'pre-push': 'compress'
			}
		},

		replace: {
			version_php: {
				src: [
					'**/*.php',
					'!vendor/**',
				],
				overwrite: true,
				replacements: [ {
						from: /Version:(\s*?)[a-zA-Z0-9\.\-\+]+$/m,
						to: 'Version:$1' + pkg.version
				}, {
						from: /@version(\s*?)[a-zA-Z0-9\.\-\+]+$/m,
						to: '@version$1' + pkg.version
				}, {
						from: /@since(.*?)NEXT/mg,
						to: '@since$1' + pkg.version
				}, {
						from: /VERSION(\s*?)=(\s*?['"])[a-zA-Z0-9\.\-\+]+/mg,
						to: 'VERSION$1=$2' + pkg.version
				} ]
			},
			version_readme: {
				src: ['README.md', 'readme.txt'],
				overwrite: true,
				replacements: [ {
						from: /^\*\*Stable tag:\*\*(\s*?)[a-zA-Z0-9.-]+(\s*?)$/mi,
						to: '**Stable tag:**$1<%= pkg.version %>$2'
				} ]
			},
		},

		compress: {
			main: {
				options: {
					mode: 'zip',
					archive: 'gc-sermons.zip'
				},
				files: [ {
						expand: true,
						src: [
							'**',
							'!**/**dandelion**.yml',
							'!**/**.xml',
							'!**/Dockunit.json',
							'!**/package.json',
							'!**/node_modules/**',
							'!**/bin/**',
							'!**/tests/**',
							'!**/sass/**',
							'!**.zip',
							'!**/**.orig',
							'!**/**.map',
							'!**/**Gruntfile.js',
							'!**/**composer.json',
							'!**/**composer.lock',
							'!**/**bower.json',
 							'!vendor/tgmpa/tgm-plugin-activation/plugins/**'
						],
						dest: '/gc-sermons'
				} ]
			}
		},

		githooks: {
			all: {
				// create zip and deploy changes to ftp
				'pre-push': 'compress'
			}
		}

	} );

	// Default task.
	grunt.registerTask( 'scripts', [] );
	grunt.registerTask( 'styles', [] );
	grunt.registerTask( 'php', [ 'addtextdomain', 'makepot' ] );
	grunt.registerTask( 'default', ['styles', 'scripts', 'php', 'compress'] );

	grunt.registerTask( 'version', [ 'default', 'replace:version_php', 'replace:version_readme', 'compress' ] );

	grunt.util.linefeed = '\n';
};
