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
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

use theme_cass\admin_setting_configurl;
use theme_cass\admin_setting_configcourseid;
use theme_cass\admin_setting_configradiobuttons;


$ADMIN->add('themes', new admin_category('theme_cass', 'Cass'));
$settings = null; // Unsets the default $casssettings object initialised by Moodle.

// Basic settings.
$casssettings = new admin_settingpage('themesettingcass', 'Cass');

// Feature spots settings.
$fssettings = new admin_settingpage('themecassfeaturespots', get_string('featurespots', 'theme_cass'));

// Featured courses settings.
$fcsettings = new admin_settingpage('themecassfeaturedcourses', get_string('featuredcourses', 'theme_cass'));

// Feature spots settings.
$resourcesettings = new admin_settingpage('themecassresourcedisplay', get_string('resourcedisplay', 'theme_cass'));

if ($ADMIN->fulltree) {

    $checked = '1';
    $unchecked = '0';

    // Output flex page front page warning if necessary.
    $fpwarning = \theme_cass\output\shared::flexpage_frontpage_warning();
    if (!empty($fpwarning)) {
        $setting = new admin_setting_heading('flexpage_warning', '', $fpwarning);
        $casssettings->add($setting);
    }

    $name = 'theme_cass/brandingheading';
    $title = new lang_string('brandingheading', 'theme_cass');
    $description = new lang_string('brandingheadingdesc', 'theme_cass');
    $setting = new admin_setting_heading($name, $title, $description);
    $casssettings->add($setting);

    if (!during_initial_install() && !empty(get_site()->fullname)) {
        // Site name setting.
        $name = 'fullname';
        $title = new lang_string('fullname', 'theme_cass');
        $description = new lang_string('fullnamedesc', 'theme_cass');
        $setting = new admin_setting_sitesettext($name, $title, $description, null);
        $casssettings->add($setting);
    }

    // Site description setting.
    $name = 'theme_cass/subtitle';
    $title = new lang_string('subtitle', 'theme_cass');
    $description = new lang_string('subtitle_desc', 'theme_cass');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $casssettings->add($setting);

    // Main theme colour setting.
    $name = 'theme_cass/themecolor';
    $title = new lang_string('themecolor', 'theme_cass');
    $description = new lang_string('themecolordesc', 'theme_cass');
    $default = '#3bcedb';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
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
    $opts = array('accepted_types' => array('.ico'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);

    // Cover image file setting.
    $name = 'theme_cass/poster';
    $title = new lang_string('poster', 'theme_cass');
    $description = new lang_string('posterdesc', 'theme_cass');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'poster', 0, $opts);
    $setting->set_updatedcallback('theme_cass_process_site_coverimage');
    $casssettings->add($setting);

    // Personal menu settings.
    $name = 'theme_cass/personalmenu';
    $title = new lang_string('personalmenu', 'theme_cass');
    $description = new lang_string('footerheadingdesc', 'theme_cass');
    $setting = new admin_setting_heading($name, $title, $description);
    $casssettings->add($setting);

    // Personal menu display on login on/off.
    $name = 'theme_cass/personalmenulogintoggle';
    $title = new lang_string('personalmenulogintoggle', 'theme_cass');
    $description = new lang_string('personalmenulogintoggledesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);

    // Personal menu deadlines on/off.
    $name = 'theme_cass/deadlinestoggle';
    $title = new lang_string('deadlinestoggle', 'theme_cass');
    $description = new lang_string('deadlinestoggledesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);

    // Personal menu recent feedback & grading  on/off.
    $name = 'theme_cass/feedbacktoggle';
    $title = new lang_string('feedbacktoggle', 'theme_cass');
    $description = new lang_string('feedbacktoggledesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);

    // Personal menu messages on/off.
    $name = 'theme_cass/messagestoggle';
    $title = new lang_string('messagestoggle', 'theme_cass');
    $description = new lang_string('messagestoggledesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);

    // Personal menu forum posts on/off.
    $name = 'theme_cass/forumpoststoggle';
    $title = new lang_string('forumpoststoggle', 'theme_cass');
    $description = new lang_string('forumpoststoggledesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);

    // Personal menu show course grade in cards.
    $name = 'theme_cass/showcoursegradepersonalmenu';
    $title = new lang_string('showcoursegradepersonalmenu', 'theme_cass');
    $description = new lang_string('showcoursegradepersonalmenudesc', 'theme_cass');
    $default = $checked; // For new installations (legacy is unchecked via upgrade.php).
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);

    // Navigation.
    $name = 'theme_cass/navigationheading';
    $title = new lang_string('navigationheading', 'theme_cass');
    $description = new lang_string('navigationheadingdesc', 'theme_cass');
    $setting = new admin_setting_heading($name, $title, $description);
    $casssettings->add($setting);

    // Hide navigation block.
    $name = 'theme_cass/hidenavblock';
    $title = new lang_string('hidenavblock', 'theme_cass');
    $description = new lang_string('hidenavblockdesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);

    // Hide quiz navigation for non editors.
    $name = 'theme_cass/hidequiznavigation';
    $title = new lang_string('hidequiznavigation', 'theme_cass');
    $description = new lang_string('hidequiznavigationdesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);
    
    // Breadcrumbs in nav bar.
    $name = 'theme_cass/breadcrumbsinnav';
    $title = new lang_string('breadcrumbsinnav', 'theme_cass');
    $description = new lang_string('breadcrumbsinnavdesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);
    
    // Fix header to top of page
    $name = 'theme_cass/fixheadertotopofpage';
    $title = new lang_string('fixheadertotopofpage', 'theme_cass');
    $description = new lang_string('fixheadertotopofpagedesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);
    
    // Show stepper count on activities for section zero
    $name = 'theme_cass/showstepperonsectionzero';
    $title = new lang_string('showstepperonsectionzero', 'theme_cass');
    $description = new lang_string('showstepperonsectionzerodesc', 'theme_cass');
    $default = $unchecked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);
    
    // Collapse completed activities via the course renderer
    $name = 'theme_cass/collapsecompletedactivities';
    $title = new lang_string('collapsecompletedactivities', 'theme_cass');
    $description = new lang_string('collapsecompletedactivitiesdesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);
    
    // Embed the current activity directly to the course renderer
    $name = 'theme_cass/embedcurrentactivity';
    $title = new lang_string('embedcurrentactivity', 'theme_cass');
    $description = new lang_string('embedcurrentactivitydesc', 'theme_cass');
    $default = $unchecked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);
    
    // Course page redirect.
    $name = 'theme_cass/coursepageredirect';
    $title = new lang_string('coursepageredirect', 'theme_cass');
    $description = new lang_string('coursepageredirectdesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);
       
    // On completion, display next activity in footer on/off.
    $name = 'theme_cass/nextactivityinfooter';
    $title = new lang_string('nextactivityinfooter', 'theme_cass');
    $description = new lang_string('nextactivityinfooterdesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);
    
    // On activity completion, popup modal dialog with link to next activity on/off.
    $name = 'theme_cass/nextactivitymodaldialog';
    $title = new lang_string('nextactivitymodaldialog', 'theme_cass');
    $description = new lang_string('nextactivitymodaldialogdesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $casssettings->add($setting);
    
    // Number of seconds after completion event to continue generating the modal dialog. Default 30.
    // Popup Modal tolerance (seconds)
    $name = 'theme_cass/nextactivitymodaldialogtolerance';
    $title = new lang_string('nextactivitymodaldialogtolerance', 'theme_cass');
    $description = new lang_string('nextactivitymodaldialogtolerancedesc', 'theme_cass');
    $default = 30;
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $casssettings->add($setting);
    
    // Number of milliseconds after page load to pop the completion modal. Default 2000.
    $name = 'theme_cass/nextactivitymodaldialogdelay';
    $title = new lang_string('nextactivitymodaldialogdelay', 'theme_cass');
    $description = new lang_string('nextactivitymodaldialogdelaydesc', 'theme_cass');
    $default = 2000;
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $casssettings->add($setting);
    
    // Logout redirection
    $name = 'theme_cass/logoutredirection';
    $title = new lang_string('logoutredirection', 'theme_cass');
    $description = new lang_string('logoutredirectiondesc', 'theme_cass');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);
    
    // Functional Heading.
    $name = 'theme_cass/functionalheading';
    $title = new lang_string('functionalheading', 'theme_cass');
    $description = new lang_string('functionalheadingdesc', 'theme_cass');
    $setting = new admin_setting_heading($name, $title, $description);
    $casssettings->add($setting); 
    
    // Semantic activation for question types on/off.
    // There is a body of knowledge that says a learner is able to answer questions better if they are presented with
    // information about how they are intended to answer BEFORE reading the question text, as opposed to simply listing
    // the word "Question" followed by the integer of the current question.
    // e.g.
    // If this setting is enabled a truefalse question type is rendered "True / False" prior to the question text rather than: "Question 1",
    // which gives no information about how the learner is expected to answer, and really, gives no information at all.
    
    $name = 'theme_cass/questionsemanticactivation';
    $title = new lang_string('questionsemanticactivation', 'theme_cass');
    $description = new lang_string('questionsemanticactivationdesc', 'theme_cass');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);
    
    // Display: "Question x of y" before each question in a quiz activity
    $name = 'theme_cass/displayquestionxofy';
    $title = new lang_string('displayquestionxofy', 'theme_cass');
    $description = new lang_string('displayquestionxofydesc', 'theme_cass');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);
        
    // Visual signal to indicate the first activity on/off.
    $name = 'theme_cass/highlightfirstactivityinsection';
    $title = new lang_string('highlightfirstactivityinsection', 'theme_cass');
    $description = new lang_string('highlightfirstactivityinsectiondesc', 'theme_cass');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);
        
    // Footer.
    $name = 'theme_cass/footerheading';
    $title = new lang_string('footerheading', 'theme_cass');
    $description = new lang_string('footerheadingdesc', 'theme_cass');
    $setting = new admin_setting_heading($name, $title, $description);
    $casssettings->add($setting);    
    

    // Course footer on/off.
    $name = 'theme_cass/coursefootertoggle';
    $title = new lang_string('coursefootertoggle', 'theme_cass');
    $description = new lang_string('coursefootertoggledesc', 'theme_cass');
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);

    // Custom footer setting.
    $name = 'theme_cass/footnote';
    $title = new lang_string('footnote', 'theme_cass');
    $description = new lang_string('footnotedesc', 'theme_cass');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);

    // Custom copyright notice.
    $name = 'theme_cass/copyrightnotice';
    $title = new lang_string('copyrightnotice', 'theme_cass');
    $description = new lang_string('copyrightnoticedesc', 'theme_cass');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);
    

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
    $default = '"Roboto"';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $casssettings->add($setting);

    // Serif font setting.
    $name = 'theme_cass/seriffont';
    $title = new lang_string('seriffont', 'theme_cass');
    $description = new lang_string('seriffont_desc', 'theme_cass');
    $default = '"Georgia"';
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

    // Left toc option.
    $name = 'theme_cass/leftnav';
    $title = new lang_string('leftnav', 'theme_cass');
    $description = new lang_string('leftnavdesc', 'theme_cass');
    $default = $unchecked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
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

    // Feature spots settings.
    // Feature spot instructions.
    $name = 'theme_cass/fs_instructions';
    $heading = '';
    $description = get_string('featurespotshelp', 'theme_cass');
    $setting = new admin_setting_heading($name, $heading, $description);
    $fssettings->add($setting);

    // Feature spots heading.
    $name = 'theme_cass/fs_heading';
    $title = new lang_string('featurespotsheading', 'theme_cass');
    $description = '';
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW, 50);
    $fssettings->add($setting);

    // Feature spot images.
    $name = 'theme_cass/fs_one_image';
    $title = new lang_string('featureoneimage', 'theme_cass');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fs_one_image', 0, $opts);
    $fssettings->add($setting);

    $name = 'theme_cass/fs_two_image';
    $title = new lang_string('featuretwoimage', 'theme_cass');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fs_two_image', 0, $opts);
    $fssettings->add($setting);

    $name = 'theme_cass/fs_three_image';
    $title = new lang_string('featurethreeimage', 'theme_cass');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fs_three_image', 0, $opts);
    $fssettings->add($setting);

    // Feature spot titles.
    $name = 'theme_cass/fs_one_title';
    $title = new lang_string('featureonetitle', 'theme_cass');
    $description = '';
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $fssettings->add($setting);

    $name = 'theme_cass/fs_two_title';
    $title = new lang_string('featuretwotitle', 'theme_cass');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $fssettings->add($setting);

    $name = 'theme_cass/fs_three_title';
    $title = new lang_string('featurethreetitle', 'theme_cass');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $fssettings->add($setting);

    // Feature spot text.
    $name = 'theme_cass/fs_one_text';
    $title = new lang_string('featureonetext', 'theme_cass');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $fssettings->add($setting);

    $name = 'theme_cass/fs_two_text';
    $title = new lang_string('featuretwotext', 'theme_cass');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $fssettings->add($setting);

    $name = 'theme_cass/fs_three_text';
    $title = new lang_string('featurethreetext', 'theme_cass');
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $fssettings->add($setting);

    // Featured courses instructions.
    $name = 'theme_cass/fc_instructions';
    $heading = '';
    $description = get_string('featuredcourseshelp', 'theme_cass');
    $setting = new admin_setting_heading($name, $heading, $description);
    $fcsettings->add($setting);

    // Featured courses heading.
    $name = 'theme_cass/fc_heading';
    $title = new lang_string('featuredcoursesheading', 'theme_cass');
    $description = '';
    $default = new lang_string('featuredcourses', 'theme_cass');
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW_TRIMMED, 50);
    $fcsettings->add($setting);

    // Featured courses.
    $name = 'theme_cass/fc_one';
    $title = new lang_string('featuredcourseone', 'theme_cass');
    $description = '';
    $default = '0';
    $setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
    $fcsettings->add($setting);

    $name = 'theme_cass/fc_two';
    $title = new lang_string('featuredcoursetwo', 'theme_cass');
    $setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
    $fcsettings->add($setting);

    $name = 'theme_cass/fc_three';
    $title = new lang_string('featuredcoursethree', 'theme_cass');
    $setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
    $fcsettings->add($setting);

    $name = 'theme_cass/fc_four';
    $title = new lang_string('featuredcoursefour', 'theme_cass');
    $setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
    $fcsettings->add($setting);

    $name = 'theme_cass/fc_five';
    $title = new lang_string('featuredcoursefive', 'theme_cass');
    $setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
    $fcsettings->add($setting);

    $name = 'theme_cass/fc_six';
    $title = new lang_string('featuredcoursesix', 'theme_cass');
    $setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
    $fcsettings->add($setting);

    $name = 'theme_cass/fc_seven';
    $title = new lang_string('featuredcourseseven', 'theme_cass');
    $setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
    $fcsettings->add($setting);

    $name = 'theme_cass/fc_eight';
    $title = new lang_string('featuredcourseeight', 'theme_cass');
    $setting = new admin_setting_configcourseid($name, $title, $description, $default, PARAM_RAW_TRIMMED);
    $fcsettings->add($setting);

    // Browse all courses link.
    $name = 'theme_cass/fc_browse_all';
    $title = new lang_string('featuredcoursesbrowseall', 'theme_cass');
    $description = new lang_string('featuredcoursesbrowsealldesc', 'theme_cass');
    $checked = '1';
    $unchecked = '0';
    $default = $unchecked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $fcsettings->add($setting);

    // Resource display help text.
    $name = 'theme_cass/resourcedisplayhelp';
    $heading = '';
    $description = get_string('resourcedisplayhelp', 'theme_cass');
    $setting = new admin_setting_heading($name, $heading, $description);
    $resourcesettings->add($setting);

    // Resource display options.
    $name = 'theme_cass/resourcedisplay';
    $title = new lang_string('resourcedisplay', 'theme_cass');
    $card = new lang_string('card', 'theme_cass');
    $list = new lang_string('list', 'theme_cass');
    $radios = array('list' => $list, 'card' => $card);
    $default = 'card';
    $description = '';
    $setting = new admin_setting_configradiobuttons($name, $title, $description, $default, $radios);
    $resourcesettings->add($setting);
}

// Add theme pages.
$ADMIN->add('theme_cass', $casssettings);
$ADMIN->add('theme_cass', $fssettings);
$ADMIN->add('theme_cass', $fcsettings);
$ADMIN->add('theme_cass', $resourcesettings);
