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
 * Snap settings.
 *
 * @package   theme_snap
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once(__DIR__.'/renderers/snap_shared.php');

if ($ADMIN->fulltree) {

    // Output flex page front page warning if necessary.
    $fpwarning = snap_shared::flexpage_frontpage_warning();
    if (!empty($fpwarning)) {
        $setting = new admin_setting_heading('flexpage_warning', '', $fpwarning);
        $settings->add($setting);
    }

    $name = 'theme_snap/brandingheading';
    $title = new lang_string('brandingheading', 'theme_snap');
    $description = new lang_string('brandingheadingdesc', 'theme_snap');
    $setting = new admin_setting_heading($name, $title, $description);
    $settings->add($setting);

    if (!during_initial_install() && !empty(get_site()->fullname)) {
        // Site name setting.
        $name = 'fullname';
        $title = new lang_string('fullname', 'theme_snap');
        $description = new lang_string('fullnamedesc', 'theme_snap');
        $setting = new admin_setting_sitesettext($name, $title, $description, null);
        $settings->add($setting);
    }

    // Site description setting.
    $name = 'theme_snap/subtitle';
    $title = new lang_string('subtitle', 'theme_snap');
    $description = new lang_string('subtitle_desc', 'theme_snap');
    $setting = new admin_setting_configtextarea($name, $title, $description, '');
    $settings->add($setting);

    // Main theme colour setting.
    $name = 'theme_snap/themecolor';
    $title = new lang_string('themecolor', 'theme_snap');
    $description = new lang_string('themecolordesc', 'theme_snap');
    $default = '#3bcedb';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

     // Logo file setting.
    $name = 'theme_snap/logo';
    $title = new lang_string('logo', 'theme_snap');
    $description = new lang_string('logodesc', 'theme_snap');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Favicon file setting.
    $name = 'theme_snap/favicon';
    $title = new lang_string('favicon', 'theme_snap');
    $description = new lang_string('favicondesc', 'theme_snap');
    $opts = array('accepted_types' => array('.ico'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Cover image file setting.
    $name = 'theme_snap/poster';
    $title = new lang_string('poster', 'theme_snap');
    $description = new lang_string('posterdesc', 'theme_snap');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'poster', 0, $opts);
    $setting->set_updatedcallback('theme_snap_process_site_coverimage');
    $settings->add($setting);
   
    // Personal menu settings
    $name = 'theme_snap/personalmenu';
    $title = new lang_string('personalmenu', 'theme_snap');
    $description = new lang_string('footerheadingdesc', 'theme_snap');
    $setting = new admin_setting_heading($name, $title, $description);
    $settings->add($setting);

    // Personal menu deadlines on/off.
    $name = 'theme_snap/deadlinestoggle';
    $title = new lang_string('deadlinestoggle', 'theme_snap');
    $description = new lang_string('deadlinestoggledesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Personal menu recent feedback & grading  on/off.
    $name = 'theme_snap/feedbacktoggle';
    $title = new lang_string('feedbacktoggle', 'theme_snap');
    $description = new lang_string('feedbacktoggledesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Personal menu messages on/off.
    $name = 'theme_snap/messagestoggle';
    $title = new lang_string('messagestoggle', 'theme_snap');
    $description = new lang_string('messagestoggledesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Personal menu forum posts on/off.
    $name = 'theme_snap/forumpoststoggle';
    $title = new lang_string('forumpoststoggle', 'theme_snap');
    $description = new lang_string('forumpoststoggledesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Navigation.
    $name = 'theme_snap/navigationheading';
    $title = new lang_string('navigationheading', 'theme_snap');
    $description = new lang_string('navigationheadingdesc', 'theme_snap');
    $setting = new admin_setting_heading($name, $title, $description);
    $settings->add($setting);    

    // Hide navigation block.
    $name = 'theme_snap/hidenavblock';
    $title = new lang_string('hidenavblock', 'theme_snap');
    $description = new lang_string('hidenavblockdesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $settings->add($setting);
    
    // Hide quiz navigation for non editors.
    $name = 'theme_snap/hidequiznavigation';
    $title = new lang_string('hidequiznavigation', 'theme_snap');
    $description = new lang_string('hidequiznavigationdesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $settings->add($setting);
    
    // Breadcrumbs in nav bar.
    $name = 'theme_snap/breadcrumbsinnav';
    $title = new lang_string('breadcrumbsinnav', 'theme_snap');
    $description = new lang_string('breadcrumbsinnavdesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $settings->add($setting);
    
    // Fix header to top of page
    $name = 'theme_snap/fixheadertotopofpage';
    $title = new lang_string('fixheadertotopofpage', 'theme_snap');
    $description = new lang_string('fixheadertotopofpagedesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $settings->add($setting);
    
    // Collapse completed activities via the course renderer
    $name = 'theme_snap/collapsecompletedactivities';
    $title = new lang_string('collapsecompletedactivities', 'theme_snap');
    $description = new lang_string('collapsecompletedactivitiesdesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $settings->add($setting);
    
    // Embed the current activity directly to the course renderer
    $name = 'theme_snap/embedcurrentactivity';
    $title = new lang_string('embedcurrentactivity', 'theme_snap');
    $description = new lang_string('embedcurrentactivitydesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $unchecked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $settings->add($setting);
    
    // Course page redirect.
    $name = 'theme_snap/coursepageredirect';
    $title = new lang_string('coursepageredirect', 'theme_snap');
    $description = new lang_string('coursepageredirectdesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $settings->add($setting);
       
    // On completion, display next activity in footer on/off.
    $name = 'theme_snap/nextactivityinfooter';
    $title = new lang_string('nextactivityinfooter', 'theme_snap');
    $description = new lang_string('nextactivityinfooterdesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $settings->add($setting);
    
    // On activity completion, popup modal dialog with link to next activity on/off.
    $name = 'theme_snap/nextactivitymodaldialog';
    $title = new lang_string('nextactivitymodaldialog', 'theme_snap');
    $description = new lang_string('nextactivitymodaldialogdesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $settings->add($setting);
    
    // Number of seconds after completion event to continue generating the modal dialog. Default 30.
    // Popup Modal tolerance (seconds)
    $name = 'theme_snap/nextactivitymodaldialogtolerance';
    $title = new lang_string('nextactivitymodaldialogtolerance', 'theme_snap');
    $description = new lang_string('nextactivitymodaldialogtolerancedesc', 'theme_snap');
    $default = 30;
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $settings->add($setting);
    
    // Number of milliseconds after page load to pop the completion modal. Default 2000.
    $name = 'theme_snap/nextactivitymodaldialogdelay';
    $title = new lang_string('nextactivitymodaldialogdelay', 'theme_snap');
    $description = new lang_string('nextactivitymodaldialogdelaydesc', 'theme_snap');
    $default = 2000;
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $settings->add($setting);
    
    // Functional Heading.
    $name = 'theme_snap/functionalheading';
    $title = new lang_string('functionalheading', 'theme_snap');
    $description = new lang_string('functionalheadingdesc', 'theme_snap');
    $setting = new admin_setting_heading($name, $title, $description);
    $settings->add($setting); 
    
    // Semantic activation for question types on/off.
    // There is a body of knowledge that says a learner is able to answer questions better if they are presented with
    // information about how they are intended to answer BEFORE reading the question text, as opposed to simply listing
    // the word "Question" followed by the integer of the current question.
    // e.g.
    // If this setting is enabled a truefalse question type is rendered "True / False" prior to the question text rather than: "Question 1",
    // which gives no information about how the learner is expected to answer, and really, gives no information at all.
    
    $name = 'theme_snap/questionsemanticactivation';
    $title = new lang_string('questionsemanticactivation', 'theme_snap');
    $description = new lang_string('questionsemanticactivationdesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    // Display: "Question x of y" before each question in a quiz activity
    $name = 'theme_snap/displayquestionxofy';
    $title = new lang_string('displayquestionxofy', 'theme_snap');
    $description = new lang_string('displayquestionxofydesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
        
    // Visual signal to indicate the first activity on/off.
    $name = 'theme_snap/highlightfirstactivityinsection';
    $title = new lang_string('highlightfirstactivityinsection', 'theme_snap');
    $description = new lang_string('highlightfirstactivityinsectiondesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
        
    // Footer.
    $name = 'theme_snap/footerheading';
    $title = new lang_string('footerheading', 'theme_snap');
    $description = new lang_string('footerheadingdesc', 'theme_snap');
    $setting = new admin_setting_heading($name, $title, $description);
    $settings->add($setting);    
    
    // Course footer on/off.
    $name = 'theme_snap/coursefootertoggle';
    $title = new lang_string('coursefootertoggle', 'theme_snap');
    $description = new lang_string('coursefootertoggledesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Custom footer setting.
    $name = 'theme_snap/footnote';
    $title = new lang_string('footnote', 'theme_snap');
    $description = new lang_string('footnotedesc', 'theme_snap');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Custom copyright notice.
    $name = 'theme_snap/copyrightnotice';
    $title = new lang_string('copyrightnotice', 'theme_snap');
    $description = new lang_string('copyrightnoticedesc', 'theme_snap');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    // Advanced branding heading.
    $name = 'theme_snap/advancedbrandingheading';
    $title = new lang_string('advancedbrandingheading', 'theme_snap');
    $description = new lang_string('advancedbrandingheadingdesc', 'theme_snap');
    $setting = new admin_setting_heading($name, $title, $description);
    $settings->add($setting);

    // Heading font setting.
    $name = 'theme_snap/headingfont';
    $title = new lang_string('headingfont', 'theme_snap');
    $description = new lang_string('headingfont_desc', 'theme_snap');
    $default = '"Roboto"';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Serif font setting.
    $name = 'theme_snap/seriffont';
    $title = new lang_string('seriffont', 'theme_snap');
    $description = new lang_string('seriffont_desc', 'theme_snap');
    $default = '"Georgia"';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    // Font include.
    $fontloader = 'theme_snap/fontloader';
    $title = new lang_string('fontloader', 'theme_snap');
    $description = new lang_string('fontloaderdesc', 'theme_snap');
    $default = new lang_string('fontloaderdefault', 'theme_snap');
    $setting = new admin_setting_configtextarea($fontloader, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Custom CSS file.
    $name = 'theme_snap/customcss';
    $title = new lang_string('customcss', 'theme_snap');
    $description = new lang_string('customcssdesc', 'theme_snap');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
    
    // CSS Post Process on/off.
    $name = 'theme_snap/csspostprocesstoggle';
    $title = new lang_string('csspostprocesstoggle', 'theme_snap');
    $description = new lang_string('csspostprocesstoggledesc', 'theme_snap');
    $checked = '1';
    $unchecked = '0';
    $default = $checked;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, $checked, $unchecked);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
}
