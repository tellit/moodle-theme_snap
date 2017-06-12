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

/**
 * Cass course renderer.
 * Overrides core course renderer.
 *
 * @package   theme_cass
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_cass\output\core;
 
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/quiz/renderer.php');

class mod_quiz_renderer extends \mod_quiz_renderer {
//class theme_cass_mod_quiz_renderer extends mod_quiz_renderer {   
    
    /**
     * Outputs the navigation block panel
     *
     * @param quiz_nav_panel_base $panel instance of quiz_nav_panel_base
     */
    public function navigation_panel(quiz_nav_panel_base $panel) {
        
        // Quiz navigation panel makes the screen too "busy" for normal users.
        // Based on a theme setting.
        if ($this->page->theme->settings->hidequiznavigation) {
            if (has_capability('mod/quiz:manage', $this->page->context)) {
                return parent::navigation_panel($panel);
            }
        }
        return '';
    }
    
}