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
 * Snap AJAX handler
 *
 * @package   theme_snap
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_snap\controller\kernel;
use theme_snap\controller\router;

define('AJAX_SCRIPT', true);
define('NO_DEBUG_DISPLAY', true);

require_once(__DIR__.'/../../config.php');

$systemcontext = context_system::instance();

$action    = required_param('action', PARAM_ALPHAEXT);
$contextid = optional_param('contextid', $systemcontext->id, PARAM_INT);

list($context, $course, $cm) = get_context_info_array($contextid);

require_login($course, false, $cm, false, true);
/** @var $PAGE moodle_page */
$PAGE->set_context($context);
$PAGE->set_url('/theme/snap/rest.php', array('action' => $action, 'contextid' => $context->id));
if (isset($course)) $PAGE->set_course($course);
if (is_object($cm)) $PAGE->set_cm($cm);

$router = new router();

// Add controllers automatically.
$controllerdir = __DIR__.'/classes/controller';
$contfiles = scandir($controllerdir);
foreach ($contfiles as $contfile) {
    if ($contfile === 'addsection_controller.php') {
        continue;
    }
    $pattern = '/_controller.php$/i';
    if (preg_match($pattern, $contfile) !== 1) {
        continue;
    } else {
        $classname = '\\theme_snap\\controller\\'.str_ireplace('.php', '', $contfile);
        if (class_exists($classname)) {
            $rc = new ReflectionClass($classname);
            if ($rc->isSubclassOf('\\theme_snap\\controller\\controller_abstract')) {
                $router->add_controller(new $classname());
            }
        }
    }
}

$kernel = new kernel($router);
$kernel->handle($action);
