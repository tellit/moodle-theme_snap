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

$casssettings = new admin_settingpage('themecasscoursedisplay', get_string('coursedisplay', 'theme_cass'));

// Course toc display options.
$name = 'theme_cass/leftnav';
$title = new lang_string('leftnav', 'theme_cass');
$list = get_string('list', 'theme_cass');
$top = get_string('top', 'theme_cass');
$radios = array('list' => $list, 'top' => $top);
$default = 'list';
$description = new lang_string('leftnavdesc', 'theme_cass');
$setting = new admin_setting_configradiobuttons($name, $title, $description, $default, $radios);
$casssettings->add($setting);

// Resource display options.
$name = 'theme_cass/resourcedisplay';
$title = new lang_string('resourcedisplay', 'theme_cass');
$card = new lang_string('card', 'theme_cass');
$list = new lang_string('list', 'theme_cass');
$radios = array('list' => $list, 'card' => $card);
$default = 'card';
$description = get_string('resourcedisplayhelp', 'theme_cass');
$setting = new admin_setting_configradiobuttons($name, $title, $description, $default, $radios);
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
$default = $unchecked;
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
$default = 15;
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
$default = 'https://webapps.city.ac.uk/globalfinancemsc/';
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
$casssettings->add($setting);

$settings->add($casssettings);
