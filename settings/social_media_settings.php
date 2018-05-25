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

use theme_cass\admin_setting_configurl;

$casssettings = new admin_settingpage('themecasssocialmedia', get_string('socialmedia', 'theme_cass'));

    // Social media.
    $name = 'theme_cass/facebook';
    $title = new lang_string('facebook', 'theme_cass');
    $description = new lang_string('facebookdesc', 'theme_cass');
    $default = '';
    $setting = new admin_setting_configurl($name, $title, $description, $default);
    $casssettings->add($setting);

    $name = 'theme_cass/twitter';
    $title = new lang_string('twitter', 'theme_cass');
    $description = new lang_string('twitterdesc', 'theme_cass');
    $default = '';
    $setting = new admin_setting_configurl($name, $title, $description, $default);
    $casssettings->add($setting);

    $name = 'theme_cass/linkedin';
    $title = new lang_string('linkedin', 'theme_cass');
    $description = new lang_string('linkedindesc', 'theme_cass');
    $default = '';
    $setting = new admin_setting_configurl($name, $title, $description, $default);
    $casssettings->add($setting);

    $name = 'theme_cass/youtube';
    $title = new lang_string('youtube', 'theme_cass');
    $description = new lang_string('youtubedesc', 'theme_cass');
    $default = '';
    $setting = new admin_setting_configurl($name, $title, $description, $default);
    $casssettings->add($setting);

    $name = 'theme_cass/instagram';
    $title = new lang_string('instagram', 'theme_cass');
    $description = new lang_string('instagramdesc', 'theme_cass');
    $default = '';
    $setting = new admin_setting_configurl($name, $title, $description, $default);
    $casssettings->add($setting);

    $settings->add($casssettings);
