/**
 * This file is part of Moodle - http://moodle.org/
 *
 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   theme_snap
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
M.theme_snap = M.theme_snap || {
    courseid : false
};
M.theme_snap.core = {
    init: function(Y, courseid, contextid, courseconfig, settings, mod) {
        // Add courseid to moodle cfg variable (this is here for future proofing in case we need it)
        M.theme_snap.courseid = courseid;
        M.theme_snap.courseconfig = courseconfig;
        M.theme_snap.settings = settings;
        M.theme_snap.mod = mod;
        M.cfg.context = contextid;
        $(document).ready(snapInit);
    },
    
    addPopCompletion: function (Y) {
        if (typeof M.theme_snap.settings.nextactivitymodaldialogdelay != 'undefined') {
            var manualPopActivities = ['page', 'book', 'wiki'];
            if (manualPopActivities.indexOf(M.theme_snap.mod.modname) == -1) {

                //Using bootstrap modal
                //setTimeout(function(){$(\'#activitycompletemodal\').modal(\'show\');}, ' . $this->page->theme->settings->nextactivitymodaldialogdelay . ');
                          
                //using animate slide position fixed
                setTimeout(
                    function() {
                        popCompletion();
                    },
                    
                    // This is currently  populated on module.js init, but could be passed directly 
                    // to this function as a local parameter via js_init_call
                    M.theme_snap.settings.nextactivitymodaldialogdelay
                );
            }
        }
    }
    
};
