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
 * Cass settings.
 *
 * @package   theme_cass
 * @copyright Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$casssettings = new admin_settingpage('themecasscolorcategories', get_string('category_color', 'theme_cass'));

$name = 'theme_cass/categorycorlor';

$heading = new lang_string('category_color', 'theme_cass');
$description = new lang_string('category_color_description', 'theme_cass');
$setting = new admin_setting_heading($name, $heading, $description);
$casssettings->add($setting);

$name = 'theme_cass/category_color_palette';
$title = get_string('category_color_palette', 'theme_cass');
$description = get_string('category_color_palette_description', 'theme_cass');
$setting = new admin_setting_configcolourpicker($name, $title, $description, '');
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);

$name = 'theme_cass/category_color';
$title = get_string('jsontext', 'theme_cass');
$description = get_string('jsontextdescription', 'theme_cass');
$default = '';
$setting = new \theme_cass\admin_setting_configcolorcategory($name, $title, $description, $default);
$casssettings->add($setting);
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);

$settings->add($casssettings);