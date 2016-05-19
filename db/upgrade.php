<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

include_once($CFG->dirroot.'/theme/snap/lib.php');

/**
 * Theme upgrade
 *
 * @package   theme_snap
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function xmldb_theme_snap_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2014080400) {
        if (get_config('core', 'theme') == 'snap') {
            set_config('deadlinestoggle', 0, 'theme_snap');
            set_config('messagestoggle', 0, 'theme_snap');
        }
        upgrade_plugin_savepoint(true, 2014080400, 'theme', 'snap');
    }

    if ($oldversion < 2014090900) {
        if (get_config('core', 'theme') == 'snap') {
            set_config('coursefootertoggle', 0, 'theme_snap');
        }
        upgrade_plugin_savepoint(true, 2014090900, 'theme', 'snap');
    }

    if ($oldversion < 2014110404) {
        theme_snap_process_site_coverimage();
        upgrade_plugin_savepoint(true, 2014110404, 'theme', 'snap');
    }
    // upgrade without needing to increment version.
    // (By using the greater than or equals operator
    if ($oldversion <= 2016012600) {

        if (get_config('core', 'theme') == 'snap' || get_config('core', 'allowuserthemes')) {
            
            // Hide quiz navigation for non editors. Default to true.
            // Quiz navigation is a distraction for users attempting the quiz
            set_config('hidequiznavigation', 1, 'theme_snap');
            
            // Output the breadcrumbs in the navbar, and disable a sticky header
            set_config('breadcrumbsinnav', 1, 'theme_snap');
            
            
            set_config('collapsecompletedactivities', 0, 'theme_snap');
            set_config('embedcurrentactivity', 0, 'theme_snap');
            set_config('nextactivityinfooter', 0, 'theme_snap');
            set_config('nextactivitymodaldialog', 0, 'theme_snap');
            set_config('nextactivitymodaldialog', 0, 'theme_snap');
            set_config('nextactivitymodaldialogdelay', 0, 'theme_snap');
            set_config('coursepageredirect', 0, 'theme_snap');
            set_config('questionsemanticactivation', 0, 'theme_snap');
            set_config('displayquestionxofy', 0, 'theme_snap');
            set_config('highlightfirstactivityinsection', 0, 'theme_snap');
            set_config('copyrightnotice', 0, 'theme_snap');
            set_config('fontloader', 0, 'theme_snap');
            set_config('csspostprocesstoggle', 0, 'theme_snap');
            set_config('coursefootertoggle', 0, 'theme_snap');  
        }

        // Snap savepoint reached.
        upgrade_plugin_savepoint(true, 2016012600, 'theme', 'snap');
    }

    return true;
}
