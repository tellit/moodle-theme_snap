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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/theme/cass/lib.php');

/**
 * Theme upgrade
 *
 * @package   theme_cass
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function xmldb_theme_cass_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2014080400) {
        if (get_config('core', 'theme') == 'cass') {
            set_config('deadlinestoggle', 0, 'theme_cass');
            set_config('messagestoggle', 0, 'theme_cass');
        }
        upgrade_plugin_savepoint(true, 2014080400, 'theme', 'cass');
    }

    if ($oldversion < 2014090900) {
        if (get_config('core', 'theme') == 'cass') {
            set_config('coursefootertoggle', 0, 'theme_cass');
        }
        upgrade_plugin_savepoint(true, 2014090900, 'theme', 'cass');
    }

    if ($oldversion < 2014110404) {
        theme_cass_process_site_coverimage();
        upgrade_plugin_savepoint(true, 2014110404, 'theme', 'cass');
    }

    if ($oldversion < 2016042900) {
        // Set default value for showing personal menu on login.
        if (get_config('core', 'theme') == 'cass') {
            set_config('personalmenulogintoggle', 0, 'theme_cass');
        }

        // Cass savepoint reached.
        upgrade_plugin_savepoint(true, 2016042900, 'theme', 'cass');
    }

    if ($oldversion < 2016042904) {
        // Define table theme_cass_course_favorites to be created.
        $table = new xmldb_table('theme_cass_course_favorites');

        // Adding fields to table theme_cass_course_favorites.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timefavorited', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table theme_cass_course_favorites.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table theme_cass_course_favorites.
        $table->add_index('userid-courseid', XMLDB_INDEX_UNIQUE, array('userid', 'courseid'));

        // Conditionally launch create table for theme_cass_course_favorites.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Cass savepoint reached.
        upgrade_plugin_savepoint(true, 2016042904, 'theme', 'cass');
    }

    if ($oldversion < 2016121309) {
        if (get_config('core', 'theme') === 'cass') {
            set_config('showcoursegradepersonalmenu', 0, 'theme_cass');
        }
        upgrade_plugin_savepoint(true, 2016121309, 'theme', 'cass');
    }

    if ($oldversion < 2017122801) {
        if (!is_null(get_config('theme_cass', 'hidenavblock'))) {
            unset_config('hidenavblock', 'theme_cass');
        }
        upgrade_plugin_savepoint(true, 2017122801, 'theme', 'cass');
    }

    return true;
}
