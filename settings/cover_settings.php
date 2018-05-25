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
use theme_cass\admin_setting_configradiobuttons;

$casssettings = new admin_settingpage('themecasscoverdisplay', get_string('coverdisplay', 'theme_cass'));

$name = 'theme_cass/cover_image';
$heading = new lang_string('poster', 'theme_cass');
$description = '';
$setting = new admin_setting_heading($name, $heading, $description);
$casssettings->add($setting);

// Cover image file setting.
$name = 'theme_cass/poster';
$title = new lang_string('poster', 'theme_cass');
$description = new lang_string('posterdesc', 'theme_cass');
$opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.svg'));
$setting = new admin_setting_configstoredfile($name, $title, $description, 'poster', 0, $opts);
$setting->set_updatedcallback('theme_cass_process_site_coverimage');
$casssettings->add($setting);

// Cover carousel.
$name = 'theme_cass/cover_carousel_heading';
$heading = new lang_string('covercarousel', 'theme_cass');
$description = new lang_string('covercarouseldescription', 'theme_cass');
$setting = new admin_setting_heading($name, $heading, $description);
$casssettings->add($setting);

$name = 'theme_cass/cover_carousel';
$title = new lang_string('covercarouselon', 'theme_cass');
$description = '';
$default = $unchecked;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
$casssettings->add($setting);


$name = 'theme_cass/slide_one_image';
$title = new lang_string('coverimage', 'theme_cass');
$description = '';
$opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.svg'));
$setting = new admin_setting_configstoredfile($name, $title, $description, 'slide_one_image', 0, $opts);
$casssettings->add($setting);

$name = 'theme_cass/slide_two_image';
$title = new lang_string('coverimage', 'theme_cass');
$description = '';
$opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.svg'));
$setting = new admin_setting_configstoredfile($name, $title, $description, 'slide_two_image', 0, $opts);
$casssettings->add($setting);

$name = 'theme_cass/slide_three_image';
$title = new lang_string('coverimage', 'theme_cass');
$description = '';
$opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.svg'));
$setting = new admin_setting_configstoredfile($name, $title, $description, 'slide_three_image', 0, $opts);
$casssettings->add($setting);

$name = 'theme_cass/slide_one_title';
$title = new lang_string('title', 'theme_cass');
$description = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$casssettings->add($setting);

$name = 'theme_cass/slide_two_title';
$title = new lang_string('title', 'theme_cass');
$description = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$casssettings->add($setting);

$name = 'theme_cass/slide_three_title';
$title = new lang_string('title', 'theme_cass');
$description = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$casssettings->add($setting);

$name = 'theme_cass/slide_one_subtitle';
$title = new lang_string('subtitle', 'theme_cass');
$description = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$casssettings->add($setting);

$name = 'theme_cass/slide_two_subtitle';
$title = new lang_string('subtitle', 'theme_cass');
$description = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$casssettings->add($setting);

$name = 'theme_cass/slide_three_subtitle';
$title = new lang_string('subtitle', 'theme_cass');
$description = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$casssettings->add($setting);

$settings->add($casssettings);
