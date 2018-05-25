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

namespace theme_cass\controller;

defined('MOODLE_INTERNAL') || die();

/**
 * HSUForum Controller.
 *
 * @package   theme_cass
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hsuforummod_controller extends controller_abstract {

    /**
     * Do any security checks needed for the passed action
     *
     * @param string $action
     */
    public function require_capability($action) {
    }

    /**
     * Get the user's completion for the current mod.
     *
     * @return string
     */
    public function get_completion_action() {
        global $PAGE;

        return json_encode(array('html' =>
            \theme_cass\local::render_completion_footer(
                $PAGE->theme->settings->nextactivityinfooter,
                $PAGE->theme->settings->nextactivitymodaldialog,
                $PAGE->theme->settings->nextactivitymodaldialogtolerance
            )
        ));
    }

}