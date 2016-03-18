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


$showcompletionnextactivity = false;
$showcompletionmodal = false;

// If nextactivityinfooter or nextactivitymodaldialog are set
if ($this->page->theme->settings->nextactivityinfooter || $this->page->theme->settings->nextactivitymodaldialog) {

    //1 If we are on a mod page...
    $pagepath = explode('-', $PAGE->pagetype);
    if ($pagepath[0] == 'mod') {

        //2 Check course completion setting
        if ($COURSE->enablecompletion == COMPLETION_ENABLED) {
        
            //3 Check completion setting of current mod
            if ($PAGE->cm->completion == COMPLETION_TRACKING_MANUAL && $this->page->theme->settings->nextactivityinfooter) $showcompletionnextactivity = true;
            
            // Don't bother popping a modal if completion is based on user clicking a box (COMPLETION_TRACKING_MANUAL)
            if ($PAGE->cm->completion == COMPLETION_TRACKING_AUTOMATIC) {
                     
                //4 Check completion of current mod
                $completion = $DB->get_record('course_modules_completion', array('coursemoduleid'=>$PAGE->cm->id, 'userid'=>$USER->id));
                
                if (!empty($completion)) {                    
                    if (!empty($completion->completionstate)) {
                        if ($completion->completionstate == COMPLETION_COMPLETE || $completion->completionstate == COMPLETION_COMPLETE_PASS) {
                            
                            if ($this->page->theme->settings->nextactivityinfooter) $showcompletionnextactivity = true;
                            
                            if ($this->page->theme->settings->nextactivitymodaldialog) {
                    
                                if (!empty($completion->timemodified)) {
                                    // Use absolute value in forumla to be defensive about potential concurrency issues from multiple webservers
                                    if (abs(time() - $completion->timemodified) < $this->page->theme->settings->nextactivitymodaldialogtolerance) {
                                        if ($completion->completionstate == COMPLETION_COMPLETE || $completion->completionstate == COMPLETION_COMPLETE_PASS) {
                                            $showcompletionmodal = true;
                                        }
                                    }     
                                }
                            }
                        }
                    }
                } 
            }
        }      
    }    
}
if ($showcompletionnextactivity || $showcompletionmodal) {
    //for loop to find current and next
    $currentcmidfoundflag = false;
    $nextmod = false;
    
    $cms = $PAGE->cm->get_modinfo()->cms;
    foreach ($cms as $cmid => $cm) {
        if (!$currentcmidfoundflag) {
            if ($cmid == $PAGE->cm->id) {
                $currentcmidfoundflag = true;
                continue;
            } else {
                continue;
            }
        }
        if ($cm->uservisible) {
             $nextmod = $cm;
             break;
        }
    }
    $nextmodurl =  $nextmod->url->out();
}

if ($showcompletionmodal) { 
echo '<!-- Modal -->
<div class="modal fade activitycompletemodal" id="activitycompletemodal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content activitycompletemodal-content">
            <div class="modal-header activitycompletemodal-header">
                <span class="glyphicon glyphicon-ok activitycompletemodal-ok"></span>
            </div>
            <div class="modal-body activitycompletemodal-body">
                <h4 class="modal-title activitycompletemodal-title">
                    Activity Complete
                </h4>
                <p>You released the next activity <span class="activitycompletenextmodname">' . $nextmod->name . '</span>.
                </p>
            </div>
            <div class="modal-footer activitycompletemodal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a href="' . $nextmodurl . '" class="activitycompletenextmodlink">Next Activity
                <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                </a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(window).load(function(){
        //$(\'#myModal\').modal(\'show\');
        setTimeout(function(){$(\'#activitycompletemodal\').modal(\'show\');}, ' . $this->page->theme->settings->nextactivitymodaldialogdelay . ');
    });
</script>';
}

if ($showcompletionnextactivity) {
   echo '<a class="next_activity" href="' . $nextmodurl . '"><div class="nav_icon"><i class="icon-arrow-right"></i></div><span class="text"><span class="nav_guide">Next Activity</span><br>' . $nextmod->name . '</span></a>';
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
