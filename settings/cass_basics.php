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

$casssettings = new admin_settingpage('themecassbranding', get_string('basics', 'theme_cass'));

if (!during_initial_install() && !empty(get_site()->fullname)) {
    // Site name setting.
    $name = 'fullname';
    $title = new lang_string('fullname', 'theme_cass');
    $description = new lang_string('fullnamedesc', 'theme_cass');
    $description = '';
    $setting = new admin_setting_sitesettext($name, $title, $description, null);
    $casssettings->add($setting);
}

// Main theme colour setting.
$name = 'theme_cass/themecolor';
$title = new lang_string('themecolor', 'theme_cass');
$description = new lang_string('themecolordesc', 'theme_cass');
$default = '#8f182e';
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);

// Site description setting.
$name = 'theme_cass/subtitle';
$title = new lang_string('sitedescription', 'theme_cass');
$description = new lang_string('subtitle_desc', 'theme_cass');
$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_RAW_TRIMMED, 50);
$casssettings->add($setting);

$name = 'theme_cass/imagesheading';
$title = new lang_string('images', 'theme_cass');
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$casssettings->add($setting);

 // Logo file setting.
$name = 'theme_cass/logo';
$title = new lang_string('logo', 'theme_cass');
$description = new lang_string('logodesc', 'theme_cass');
$opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
$setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0, $opts);
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);


// Favicon file setting.
$name = 'theme_cass/favicon';
$title = new lang_string('favicon', 'theme_cass');
$description = new lang_string('favicondesc', 'theme_cass');
$opts = array('accepted_types' => array('.ico', '.png', '.gif'));
$setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, $opts);
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);

$name = 'theme_cass/footerheading';
$title = new lang_string('footnote', 'theme_cass');
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$casssettings->add($setting);

// Custom footer setting.
$name = 'theme_cass/footnote';
$title = new lang_string('footnote', 'theme_cass');
$description = new lang_string('footnotedesc', 'theme_cass');
$default = '';
$setting = new admin_setting_confightmleditor($name, $title, $description, $default);
$casssettings->add($setting);

// Custom copyright notice.
$name = 'theme_cass/copyrightnotice';
$title = new lang_string('copyrightnotice', 'theme_cass');
$description = new lang_string('copyrightnoticedesc', 'theme_cass');
$default = '&nbsp;';
$setting = new admin_setting_confightmleditor($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);

// Advanced branding heading.
$name = 'theme_cass/advancedbrandingheading';
$title = new lang_string('advancedbrandingheading', 'theme_cass');
$description = new lang_string('advancedbrandingheadingdesc', 'theme_cass');
$setting = new admin_setting_heading($name, $title, $description);
$casssettings->add($setting);

// Heading font setting.
$name = 'theme_cass/headingfont';
$title = new lang_string('headingfont', 'theme_cass');
$description = new lang_string('headingfont_desc', 'theme_cass');
$default = '"ff-meta-web-pro"';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);

// Serif font setting.
$name = 'theme_cass/seriffont';
$title = new lang_string('seriffont', 'theme_cass');
$description = new lang_string('seriffont_desc', 'theme_cass');
$default = '"ff-meta-serif-web-pro"';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);

// Font include.
$fontloader = 'theme_cass/fontloader';
$title = new lang_string('fontloader', 'theme_cass');
$description = new lang_string('fontloaderdesc', 'theme_cass');
$default = new lang_string('fontloaderdefault', 'theme_cass');
$setting = new admin_setting_configtextarea($fontloader, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);

// Custom CSS file.
$name = 'theme_cass/customcss';
$title = new lang_string('customcss', 'theme_cass');
$description = new lang_string('customcssdesc', 'theme_cass');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);

// CSS Post Process on/off.
$name = 'theme_cass/csspostprocesstoggle';
$title = new lang_string('csspostprocesstoggle', 'theme_cass');
$description = new lang_string('csspostprocesstoggledesc', 'theme_cass');
$checked = '1';
$unchecked = '0';
$default = $checked;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
$setting->set_updatedcallback('theme_reset_all_caches');
$casssettings->add($setting);


$settings->add($casssettings);
