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
 * Layout - footer.
 * This layout is baed on a moodle site index.php file but has been adapted to show news items in a different
 * way.
 *
 * @package   theme_snap
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $USER, $DB;

// If we're on a 'mod' page, retrieve the mod object and check it's completion state in order to conditionally 
// pop a completion modal and show a link to the next activity in the footer.
// Some mods should auto pop on completion, and some should display a link.

echo  html_writer::start_div('completion-region');
              
echo \theme_snap\local::render_completion_footer(
    $this->page->theme->settings->nextactivityinfooter, 
    $this->page->theme->settings->nextactivitymodaldialog,
    $this->page->theme->settings->nextactivitymodaldialogtolerance
);

echo html_writer::end_div();

$inccoursefooterclass = ($PAGE->theme->settings->coursefootertoggle && strpos($PAGE->pagetype, 'course-view-') === 0)
    ? ' hascoursefooter'
    : ' nocoursefooter';
?>
<footer id="moodle-footer" role="footer" class="clearfix<?php echo ($inccoursefooterclass)?>">
<?php
/* snap custom footer */

/* custom footer edit button - always shown */
$footnote = empty($PAGE->theme->settings->footnote) ? '' : $PAGE->theme->settings->footnote;
if ($this->page->user_is_editing() && $PAGE->pagetype == 'site-index') {
    $url = new moodle_url('/admin/settings.php', ['section' => 'themesettingsnap'], 'admin-footnote');
    $link = html_writer::link($url, get_string('editcustomfooter', 'theme_snap'), ['class' => 'btn btn-default btn-sm']);
    $footnote .= '<p class="text-right">'.$link.'</p>';
}

/* custom menu edit button - only shown if menu exists */
$custommenu = $OUTPUT->custom_menu();
if (!empty($custommenu) && $this->page->user_is_editing() && $PAGE->pagetype == 'site-index') {
    $url = new moodle_url('/admin/settings.php', ['section' => 'themesettings'], 'id_s__custommenuitems');
    $link = html_writer::link($url, get_string('editcustommenu', 'theme_snap'), ['class' => 'btn btn-default btn-sm']);
    $custommenu .= '<p class="text-right">'.$link.'</p>';
}

if (!empty($custommenu) && !empty($footnote)) {
    echo '<div class="row">';
        echo '<div class="col-md-6">';
        echo $footnote;
        echo '</div>';
        echo '<div class="col-md-6">';
        echo $custommenu;
        echo '</div>';
    echo '</div>';
} else if (!empty($footnote)) {
    echo '<div class="row">
        <div class="col-md-12">';
    echo $footnote;
    echo '</div></div>';
} else if (!empty($custommenu)) {
    echo '<div class="row">
        <div class="col-md-12">';
    echo $custommenu;
    echo '</div></div>';
}

if (core_component::get_component_directory('local_mrooms') !== null) {
    $langkey   = \local_mrooms\kb_link::resolve_language_key();
    $builtwith = html_writer::link("https://$langkey.help.blackboard.com/Moodlerooms", get_string('joule', 'theme_snap'),
        ['target' => '_blank', 'title' => get_string('joulehelpguides', 'theme_snap')]);
} else {
    $builtwith = get_string('joule', 'theme_snap');
}

$poweredbyrunby = get_string('poweredbyrunby', 'theme_snap', $builtwith);

if (empty($PAGE->theme->settings->copyrightnotice)) {
    echo '<div id="mrooms-footer" class="helplink text-right">
    <small>';
    if ($OUTPUT->page_doc_link()) {
        echo $OUTPUT->page_doc_link();
    }
    echo '<br/>' . $poweredbyrunby . '
    <br>&copy; Copyright 2016 Moodlerooms Inc, All Rights Reserved.</small>
    </div>';
} else {
    echo $PAGE->theme->settings->copyrightnotice;
}
?>
<!-- close mrooms footer -->
<div id="page-footer">
<?php echo $OUTPUT->lang_menu(); ?>
<?php echo $OUTPUT->standard_footer_html(); ?>
</div>
</footer>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
<!-- bye! -->
</body>
</html>