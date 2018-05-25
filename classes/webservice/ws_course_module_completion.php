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
 *
 * @author    Guy Thomas <gthomas@moodlerooms.com>
 * @copyright Copyright (c) 2016 Blackboard Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_cass\webservice;

use theme_cass\services\course;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../lib/externallib.php');

class ws_course_module_completion extends \external_api {
    /**
     * @return \external_function_parameters
     */
    public static function service_parameters() {
        $parameters = [
            'id' => new \external_value(PARAM_INT, 'Course module id', VALUE_REQUIRED),
            'completionstate' => new \external_value(PARAM_BOOL, 'Course module completion state', VALUE_REQUIRED)
        ];
        return new \external_function_parameters($parameters);
    }

    /**
     * @return \external_single_structure
     */
    public static function service_returns() {
        $keys = [
            'id' => new \external_value(PARAM_INT, 'course module id'),
            'completionhtml' => new \external_value(PARAM_RAW, 'completion html')
        ];
        return new \external_single_structure($keys, 'course_module_completion');
    }

    /**
     * @param int $id
     * @param string $modulename
     * @param int $completionstate 1 or 0
     * @return array
     */
    public static function service($id, $completionstate) {
        $service = course::service();
        $html = $service->module_toggle_completion($id, $completionstate);
        return [
            'id' => $id,
            'completionhtml' => $html
        ];
    }
}
