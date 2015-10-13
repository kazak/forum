'use strict';

// @todo Add banners on build files

module.exports = function (grunt) {
  
  // Time how long tasks take. Can help when optimizing build times
  require('time-grunt')(grunt);

  // Load grunt tasks automatically
  require('load-grunt-tasks')(grunt);

  var root_path = '.';

  // Configurable paths
  var config = {
      app: root_path + '/',
      dist: root_path + '/../../src/App/CallCenterBundle/Resources/public',
      tmp: root_path + '/.tmp',
      views: root_path + '/../../src/App/CallCenterBundle/Resources/views/Spa',
      bower: root_path + '/bower_components'
  };

  // Define the configuration for all the tasks
  grunt.initConfig({

    // Project settings
    config: config,

    // Watches files for changes and runs tasks based on the changed files
    watch: {
      bower: {
        files: ['<%= config.app %>/bower.json'],
        tasks: ['wiredep', 'concat:jsVendor']
      },
      js: {
        files: ['<%= config.app %>/js/**/*.js'],
        tasks: ['jshint', 'browserify', 'concat:jsPlugins', 'sf2-console:assetsInstall'],
        options: {
          livereload: true
        }
      },
      jstest: {
        files: ['<%= config.app %>/test/spec/{,*/}*.js'],
        tasks: ['test:watch']
      },
      gruntfile: {
        files: ['<%= config.app %>/Gruntfile.js']
      },
      sass: {
        files: ['<%= config.app %>/css/{,*/}*.{scss,sass}'],
        tasks: ['sass', 'autoprefixer', 'sf2-console:assetsInstall']
      },
      css: {
        files: ['<%= config.app %>/css/{,*/}*.css'],
        tasks: ['newer:copy:css', 'autoprefixer', 'sf2-console:assetsInstall']
      },
      html: {
        files: ['<%= config.app %>/html/{,*/}*.html'],
        tasks: ['copy:views']
      },
      templates: {
        files: ['<%= config.app %>/templates/**.hbs'],
        tasks: ['handlebars', 'sf2-console:assetsInstall']
      }
    },

    // Empties folders to start fresh
    clean: {
      dist: {
        files: [{
          dot: true,
          src: [
            '<%= config.tmp %>/'
            /*,
            '<%= config.dist %>/js/app.js',
            '<%= config.dist %>/js/templates.js',
            '<%= config.dist %>/js/vendor/',
            '<%= config.dist %>/css/main.css',
            '!<%= config.dist %>/.git*'
            */
          ]
        }]
      }
    },

    // Make sure code css are up to par and there are no obvious mistakes
    jshint: {
      options: {
        jshintrc: '<%= config.app %>/.jshintrc',
        reporter: require('jshint-stylish')
      },
      all: [
        'Gruntfile.js',
        '<%= config.app %>/js/{,*/}*.js',
        '!<%= config.app %>/js/vendor/*',
        'test/spec/{,*/}*.js'
      ]
    },

    // Compiles Sass to CSS and generates necessary files if requested
    sass: {
      options: {
        sourceMap: false, // set to false to log error messages
        includePaths: [config.bower]
      },
      dist: {
        files: [{
          expand: true,
          cwd: '<%= config.app %>/css',
          src: ['*.{scss,sass}'],
          dest: '<%= config.dist %>/css',
          ext: '.css'
        }]
      }
    },

    // Add vendor prefixed css
    autoprefixer: {
      options: {
        browsers: ['> 1%', 'last 2 versions', 'Firefox ESR', 'Opera 12.1']
      },
      dist: {
        files: [{
          expand: true,
          cwd: '<%= config.dist %>/css/',
          src: '{,*/}*.css',
          dest: '<%= config.dist %>/css/'
        }]
      }
    },

    // Automatically inject Bower components into the HTML file
    wiredep: {
      options: {
        cwd: '<%= config.app %>'
      },
      sass: {
        src: ['<%= config.app %>/css/{,*/}*.{scss,sass}'],
        ignorePath: /(\.\.\/){1,2}bower_components\//,
      }
    },

    // The following *-min tasks produce minified files in the dist folder
    imagemin: {
      dist: {
        files: [{
          expand: true,
          cwd: '<%= config.app %>/images',
          src: '{,*/}*.{gif,jpeg,jpg,png}',
          dest: '<%= config.dist %>/images'
        }]
      }
    },

    // Copies remaining files to places other tasks can use
    copy: {
      views: {
        expand: true,
        dot: true,
        cwd: '<%= config.app %>/html/',
        dest: '<%= config.views %>/',
        src: '*.html',
        rename: function(dest, src) {
          return dest + src + '.twig';
        }
      },
      css: {
        expand: true,
        dot: true,
        cwd: '<%= config.app %>/css',
        dest: '<%= config.dist %>/css/',
        src: '{,*/}*.css'
      },
      fonts: {
        expand: true,
        dot: true,
        cwd: '<%= config.app %>bower_components/font-awesome/',
        src: 'fonts/*',
        dest: '<%= config.dist %>'
      }
    },

    // Generates a custom Modernizr build that includes only the tests you
    // reference in your app
    modernizr: {
      dist: {
        devFile: '<%= config.app %>/bower_components/modernizr/modernizr.js',
        outputFile: '<%= config.dist %>/js/vendor/modernizr.js',
        files: {
          src: [
            '<%= config.dist %>/js/{,*/}*.js',
            '<%= config.dist %>/css/{,*/}*.css',
            '!<%= config.dist %>/js/vendor/*'
          ]
        }
      }
    },

    // Run some tasks in parallel to speed up build process
    concurrent: {
      test: [
        'copy:css'
      ],
      dist: [
        'sass',
        'copy',
        'imagemin',
        'concat'
      ]
    },

    browserify: {
      dist: {
        files: [
          {
            src: '<%= config.app %>/js/main.js',
            dest: '<%= config.dist %>/js/app.js'
          }
        ]
      }
    },

    handlebars: {
      dist: {
        options: {
          namespace: 'Templates',
          processName: function(filePath) {
            var nameArr = filePath.split('/');
            nameArr.splice(0,2);
            return nameArr.join('__').split('.hbs').join('');
          }
        },
        src: '<%= config.app %>/templates/{,*/}*.hbs',
        dest: '<%= config.dist %>/js/templates.js'
      }
    },

    'sf2-console': {
      options: {
        cwd: '../../'
      },
      assetsInstall: {
        cmd: 'assets:install'
      },
      asseticDump: {
        cmd: 'assetic:dump'
      }
    },

    concat: {
      jsVendor: {
        src: require('wiredep')().js,
        dest: '<%= config.dist %>/js/vendor.js'
      },
      jsPlugins: {
        src: [
          '<%= config.bower %>/Backbone.RPC2/backbone.rpc2.js',
          '<%= config.app %>/js/plugins/*.js'
        ],
        dest: '<%= config.dist %>/js/plugins.js'
      }
    }

  });

  grunt.registerTask('compile', [
    'clean:dist',
    'wiredep',
    'handlebars',
    'browserify',
    'concurrent:dist',
    'autoprefixer',
    'modernizr',
    'sf2-console:assetsInstall'
  ]);

  grunt.registerTask('build', [
    'compile',
    'sf2-console:asseticDump'
  ]);

  grunt.registerTask('dev', [
    'compile',
    'watch'
  ]);

  grunt.registerTask('default', [
    'build'
  ]);
};
