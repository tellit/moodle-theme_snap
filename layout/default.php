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
 * Layout - default.
 *
 * @package   theme_snap
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// If the embedcurrentactivity theme setting is set and a URL parameter
// of embed is set (to true), output nothing but the activity mod content.
// This allows the course page to 'scrape' the first page of the activity.
if ($this->page->theme->settings->embedcurrentactivity) {
    $embed = optional_param('embed', 0, PARAM_INT);
    if ($embed) {
        //Must output doctype 
        echo $OUTPUT->doctype();
        echo $OUTPUT->main_content();
        return;
    }
}

include(__DIR__.'/header.php');

// By default, assume we are not on a mod page.
$ismodpage = false;

// Do not assume mod page if user is editing.
// (This function returns true if the user is adding a new mod
// $PAGE->pagetype gets set even though the URL is not /mod/)
if (!$PAGE->user_is_editing()) {
    $pagepath = explode('-', $PAGE->pagetype);
    if ($PAGE->pagetype != 'admin' && $pagepath[0] == 'mod') {
               
        //Defensive Programming. $PAGE->cm doesn't exist if the user is editing (adding a new mod)
        //It may not exist for other reasons
        if (is_object($PAGE->cm)) {
            // We are on a activity (mod) page
            $ismodpage = true;
            $mod = $PAGE->cm;
        }
    }
}

// By default, do not add additional CSS classes to the 'page' div
$modpageclass = '';

// If we are on a mod page, and we are supposed to highlight the first activity in section
if ($ismodpage && $this->page->theme->settings->highlightfirstactivityinsection) {
    
    //Get the mod info
    $modinfo = $mod->get_modinfo();
    
    // If this activity is the first in the section
    // This ignores the possibility of hidden scetions
    if ($mod->id == $modinfo->sections[$mod->sectionnum][0]) {
        // Add an extra CSS class to the page div
        $modpageclass = ' class="firstactivityinsection"';
    }               
}
?>
<!-- moodle js hooks -->
<div id="page"<?php echo $modpageclass?>>
<div id="page-content">

<!--
////////////////////////// MAIN  ///////////////////////////////
-->
<main id="moodle-page" class="clearfix">
<div id="page-header" class="clearfix
<?php if (!empty($courseimagecss)) : ?>
 mast-image
<?php endif;?>">
<?php
    if (empty($PAGE->theme->settings->breadcrumbsinnav)) {
        echo '<div class="breadcrumb-nav" aria-label="breadcrumb">' . $OUTPUT->navbar() . '</div>';
    }
?>
<div id="page-mast">
<?php
echo $OUTPUT->page_heading();
echo $OUTPUT->course_header();
if ($PAGE->pagetype == 'site-index') {
    echo $OUTPUT->login_button();
}
?>
</div>
<?php
if ($this->page->user_is_editing() && $PAGE->pagetype == 'site-index') {
    $url = new moodle_url('/admin/settings.php', ['section' => 'themesettingsnap'], 'admin-poster');
    echo html_writer::link($url, get_string('changecoverimage', 'theme_snap'), ['class' => 'btn btn-default btn-sm']);
}
?>
</div>

<section id="region-main">
<?php
echo $OUTPUT->course_content_header();

// Ensure edit blocks button is only shown for appropriate pages.
$hasadminbutton = stripos($PAGE->button, '"adminedit"') || stripos($PAGE->button, '"edit"');

if ($hasadminbutton) {
    // List paths to black list for 'turn editting on' button here.
    // Note, to use regexs start and end with a pipe symbol - e.g. |^/report/| .
    $editbuttonblacklist = array(
        '/comment/',
        '/cohort/index.php',
        '|^/report/|',
        '|^/admin/|',
        '|^/mod/data/|',
        '/tag/manage.php',
        '/grade/edit/scale/index.php',
        '/outcome/admin.php',
        '/mod/assign/adminmanageplugins.php',
        '/message/defaultoutputs.php',
        '/theme/index.php',
        '/user/editadvanced.php',
        '/user/profile/index.php',

        '/my/indexsys.php',
        '/mnet/service/enrol/index.php',
        '/local/mrooms/view.php'
    );
    $pagepath = $PAGE->url->get_path();

    foreach ($editbuttonblacklist as $blacklisted) {
        if ($blacklisted[0] == '|' && $blacklisted[strlen($blacklisted) - 1] == '|') {
            // Use regex to determine blacklisting.
            if (preg_match ($blacklisted, $pagepath) === 1) {
                // This url path is blacklisted, stop button from being displayed.
                $PAGE->set_button('');
            }
        } else if ($pagepath == $blacklisted) {
            // This url path is blacklisted, stop button from being displayed.
            $PAGE->set_button('');
        }
    }
}

echo $OUTPUT->page_heading_button();

// On the front page, output some different content.
if ($PAGE->pagetype == 'site-index') {
    include(__DIR__.'/faux_site_index.php');
} else {
    echo $OUTPUT->main_content();
}

echo $OUTPUT->course_content_footer();

if (stripos($PAGE->bodyclasses, 'format-singleactivity') !== false ) {
    // Display course tools in single activity mode, but only on main page.
    // Current test for main page is based on the pagetype matching a regex.
    // Would be nice if there was something more direct to test.
    if (preg_match('/^mod-.*-view$/', $PAGE->pagetype)) {
        echo "<section id='coursetools' class='clearfix' tabindex='-1'>";
        echo snap_shared::coursetools_svg_icons();
        echo snap_shared::appendices();
        echo "</section>";
    }
}

?>


</section>

<?php include(__DIR__.'/moodle-blocks.php'); ?>
</main>

</div>
</div>
<!-- close moodle js hooks -->

<?php include(__DIR__.'/footer.php'); ?>