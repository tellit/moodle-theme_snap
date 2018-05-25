/**
 * Gruntfile for compiling theme_bootstrap .less files.
 *
 * This file configures tasks to be run by Grunt
 * http://gruntjs.com/ for the current theme.
 *
 * Requirements:
 * nodejs, npm, grunt-cli.
 *
 * Installation:
 * node and npm: instructions at http://nodejs.org/
 * grunt-cli: `[sudo] npm install -g grunt-cli`
 * node dependencies: run `npm install` in the root directory.
 *
 * Usage:
 * Default behaviour is to watch all .less files and compile
 * into compressed CSS when a change is detected to any and then
 * clear the theme's caches. Invoke either `grunt` or `grunt watch`
 * in the theme's root directory.
 *
 * To separately compile only moodle or editor .less files
 * run `grunt less:moodle` or `grunt less:editor` respectively.
 *
 * To only clear the theme caches invoke `grunt exec:decache` in
 * the theme's root directory.
 *
 * @package theme
 * @subpackage bootstrap
 * @author Joby Harding www.iamjoby.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

module.exports = function(grunt) {

    // We need to include the core Moodle grunt file too, otherwise we can't run tasks like "amd".
    require("grunt-load-gruntfile")(grunt);
    grunt.loadGruntfile("../../Gruntfile.js");

    // PHP strings for exec task.
    var configfile = "dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config.php'";
        
	grunt.config('wwwroot', (function(){
        const execSync = require('child_process').execSync;
        return execSync('php -r "' + "define('CLI_SCRIPT', true); require(" + configfile  + "); global $CFG; echo $CFG->wwwroot;" + '"');
	})());

    grunt.mergeConfig = grunt.config.merge;

    grunt.mergeConfig({
        exec: {
            decache: {
                cmd: 'php -r "' + "define('CLI_SCRIPT', true); require(" + configfile  + "); theme_reset_all_caches();" + '"',
                callback: function(error, stdout, stderror) {
                    // exec will output error messages
                    // just add one to confirm success.
                    if (!error) {
                        grunt.log.writeln("Moodle theme cache reset.");
                    }
                }
            }
        },
        http: {
            prime_theme_cache: {
                options: {
                    url: grunt.config('wwwroot') + '/theme/styles.php/cass/-1/all',
                },
                callback: function(error, response, body) {
                    if (!error) {
                        grunt.log.writeln("Moodle theme cache primed. Response: " + response + ".");
                    }
                }
            }
        },
        watch: {
            options: {
                spawn: false,
            },
            less: {
                files: ["scss/**/*.scss"],
                tasks: ["decache"],
            },
            amd: {
                files: ["amd/src/**/*.js"],
                tasks: ["amd","decache"],
            },
        }
    });

    // Load contrib tasks.
    grunt.loadNpmTasks("grunt-contrib-watch");
    grunt.loadNpmTasks("grunt-exec");
    grunt.loadNpmTasks("grunt-http");

    // Register tasks.
    grunt.registerTask("default", ["watch"]);
    grunt.registerTask("decache", ["exec:decache", "http:prime_theme_cache"]);
};
