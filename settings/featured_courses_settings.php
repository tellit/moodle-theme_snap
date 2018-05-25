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

defined('MOODLE_INTERNAL') || die;// Main settings.

use theme_cass\admin_setting_configcourseid;
$casssettings = new admin_settingpage('themecassfeaturedcourses', get_string('featuredcourses', 'theme_cass'));

// Featured courses instructions.
$name = 'theme_cass/fc_instructions';
$heading = '';
$description = get_string('featuredcourseshelp', 'theme_cass');
$setting = new admin_setting_heading($name, $heading, $description);
$casssettings->add($setting);

// Featured courses heading.
$name = 'theme_cass/fc_heading';
$title = new lang_string('featuredcoursesheading', 'theme_cass');
$description = '';
$default = new lang_string('featuredcourses', 'theme_cass');
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW_TRIMMED, 50);
$casssettings->add($setting);

// Featured courses.
$name = 'theme_cass/fc_one';
$title = new lang_string('featuredcourseone', 'theme_cass');
$description = '';
$default = '0';
$setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
$casssettings->add($setting);

$name = 'theme_cass/fc_two';
$title = new lang_string('featuredcoursetwo', 'theme_cass');
$setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
$casssettings->add($setting);

$name = 'theme_cass/fc_three';
$title = new lang_string('featuredcoursethree', 'theme_cass');
$setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
$casssettings->add($setting);

$name = 'theme_cass/fc_four';
$title = new lang_string('featuredcoursefour', 'theme_cass');
$setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
$casssettings->add($setting);

$name = 'theme_cass/fc_five';
$title = new lang_string('featuredcoursefive', 'theme_cass');
$setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
$casssettings->add($setting);

$name = 'theme_cass/fc_six';
$title = new lang_string('featuredcoursesix', 'theme_cass');
$setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
$casssettings->add($setting);

$name = 'theme_cass/fc_seven';
$title = new lang_string('featuredcourseseven', 'theme_cass');
$setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
$casssettings->add($setting);

$name = 'theme_cass/fc_eight';
$title = new lang_string('featuredcourseeight', 'theme_cass');
$setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
$casssettings->add($setting);

// Browse all courses link.
$name = 'theme_cass/fc_browse_all';
$title = new lang_string('featuredcoursesbrowseall', 'theme_cass');
$description = new lang_string('featuredcoursesbrowsealldesc', 'theme_cass');
$checked = '1';
$unchecked = '0';
$default = $unchecked;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
$casssettings->add($setting);

$settings->add($casssettings);
