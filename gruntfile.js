module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            dev: {
                options: {
                    quiet: true,
                    style: 'expanded'
                },
                files: {
                    'css/social-sharer.css':'scss/style.scss',
                }
            }
        },

        uglify: {
            my_target: {
                files: {
                    'js/min/cap-social-sharer.min.js': ['js/selection-sharer.js']
                }
            }
        },

        watch: {
            grunt: { files: ['gruntfile.js'] },

            sass: {
                files: 'scss/**/*.scss',
                tasks: ['sass']
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('default', ['sass:dev', 'uglify', 'watch']);
    grunt.registerTask('build', ['sass:dev', 'uglify']);
}
