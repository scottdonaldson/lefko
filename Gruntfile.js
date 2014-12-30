module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        jshint: {
            options: {
                force: true
            },
            all: ['Gruntfile.js', 'js/src/*.script.js']
        },

        uglify: {
            build: {
                files: {
                    'js/min/script.js': ['js/lib/jquery-2.1.3.js', 'js/src/plugins.js', 'js/src/script.js']
                }
            }
        },

        sass: {
            dist: {
                options: {
                    compass: true,
                    style: 'compressed'
                },
                files: {
                    'css/style.css': 'sass/style.scss'
                }
            }
        },

        autoprefixer: {
            options: {
                browsers: ['> 1%']
            },
            no_dest: {
                src: 'css/style.css'
            }
        },

        watch: {
            options: {
                livereload: true
            },
            scripts: {
                files: ['js/src/*.js'],
                tasks: ['uglify', 'jshint'],
                options: {
                    spawn: false,
                }
            },
            css: {
                files: ['sass/*.scss', 'css/style.css'],
                tasks: ['sass', 'autoprefixer'],
                options: {
                    spawn: false
                }
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['jshint', 'uglify', 'sass', 'autoprefixer', 'watch']);

};
