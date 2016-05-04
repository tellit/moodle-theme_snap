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



$showcompletionnextactivity = false;
$showcompletionmodal = false;
$mod = null;

// Don't do any of this if the user is editing
if (!$PAGE->user_is_editing()) {

    // If nextactivityinfooter or nextactivitymodaldialog are set
    if ($this->page->theme->settings->nextactivityinfooter || $this->page->theme->settings->nextactivitymodaldialog) {

        //1 If we are on a mod page...
        $pagepath = explode('-', $PAGE->pagetype);
        if ($PAGE->pagetype != 'admin' && $pagepath[0] == 'mod') {

            //2 Check course completion setting
            if ($COURSE->enablecompletion == COMPLETION_ENABLED) {
            
                if (is_object($mod)) {
                    $mod = $PAGE->cm;
                
                    //3 Check completion setting of current mod
                    if ($mod->completion == COMPLETION_TRACKING_MANUAL && $this->page->theme->settings->nextactivityinfooter) $showcompletionnextactivity = true;
                    
                    // Don't bother popping a modal if completion is based on user clicking a box (COMPLETION_TRACKING_MANUAL)
                    if ($mod->completion == COMPLETION_TRACKING_AUTOMATIC) {
                             
                        //4 Check completion of current mod
                        $completion = $DB->get_record('course_modules_completion', array('coursemoduleid'=>$mod->id, 'userid'=>$USER->id));
                        
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
    }
}
if ($showcompletionnextactivity || $showcompletionmodal) {
    //for loop to find current and next
    $currentcmidfoundflag = false;
    $nextmod = false;
    
    $cms = $mod->get_modinfo()->cms;
    foreach ($cms as $cmid => $cm) {
        if (!$currentcmidfoundflag) {
            if ($cmid == $mod->id) {
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
    
    // If there is no "next mod" then assume we are at the final mod
    if ($nextmod) {
        $forwardlinkurl =  $nextmod->url->out();
        $forwardlinkname = $nextmod->name;
        $forwardlinktext = 'Next Activity';
        $completiontext = 'You released the next activity ';
    } else {
        $courseurl = new moodle_url('/course/view.php', ['id' => $COURSE->id], 'section-' . $mod->sectionnum);        
        $forwardlinkurl =  $courseurl->out();     //course page link
        $forwardlinkname = $COURSE->fullname;
        $forwardlinktext = 'To course page';          //Back to course page
        $completiontext = 'Course page ';
    }
}

if ($showcompletionmodal) {    
                                             
echo '<!-- Modal -->

<span id = "darkBackground" class = "darkBackgroundStyle"></span>
<span id = "alertBox" class = "boxStyle">
<img style = "position: relative; display: block; width: 100px; height: 100px; top: 75px; left: 100px;" src = "http://www.combey.com/check.svg">
<span style = "position: relative; top: 125px; text-align: center; font-size: 24px; display: block">Section Complete!</span>
</span>

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
                <p>' . $completiontext . '<span class="activitycompletenextmodname">' . $forwardlinkname . '</span>.
                </p>
            </div>
            <div class="modal-footer activitycompletemodal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="scrollOut(\'#activitycompletemodal\')">Close</button>
                <a href="' . $forwardlinkurl . '" class="activitycompletenextmodlink">' . $forwardlinktext . '
                <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                </a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //$(\'[data-toggle="collapse"]\').on(\'click\', function() {
    //    $(this).toggleClass(\'glyphicon-chevron-down glyphicon-chevron-up\');
    //});
    
    
    
    //function toggleChevron(e) {
    //    $(e.target)
    //        .find(".glyphicon").toggleClass(\'glyphicon-chevron-down glyphicon-chevron-up\');
    //}
    //$(\'[data-toggle="collapse"\').on(\'hide.bs.collapse show.bs.collapse\', toggleChevron);
   
function scrollIn(selector) {
    $(selector).show().animate({right: "20px", opacity: 1}, 1000);
}    
    
function scrollOut(selector) {
    $(selector).animate({right: "-600px", opacity: 0.5}, 200, function(){ $(selector).hide()});
}

function popCompletion() {
    TweenLite.to($("#darkBackground"), 0, {display:"block"});
    TweenLite.to($("#darkBackground"), 0.3, {background:"rgba(0,0,0,0.4)", force3D:true});
    TweenLite.to($("#alertBox"), 0, {display:"block", scale:0.2, opacity: 0, delay:"0.2"});
    TweenLite.to($("#alertBox"), 0.3, {opacity: 1, force3D:true, delay:"0.2"});
    TweenLite.to($("#alertBox"), 0.6, {scale:1, scale:1, force3D:true, delay:"0.2"});
    TweenLite.to($("#darkBackground"), 0.2, {backgroundColor: "rgba(0,0,0,0)", force3D:true, delay:"2"});
    TweenLite.to($("#darkBackground"), 0.2, {display: "none", force3D:true, delay:"2"});
    TweenLite.to($("#alertBox"), 0.2, {opacity: 0, display:"none", force3D:true, delay:"2", onComplete:scrollIn(\'#activitycompletemodal\');});
}
';

    //if mod -> type == book, page, blah
    if ($mod) {

        echo '$(window).load(function(){        
            //Using bootstrap modal
            //setTimeout(function(){$(\'#activitycompletemodal\').modal(\'show\');}, ' . $this->page->theme->settings->nextactivitymodaldialogdelay . ');
            
            //using animate slide position fixed
            setTimeout(
                function() {
                    popCompletion();
                }, 
                ' . $this->page->theme->settings->nextactivitymodaldialogdelay . '
            );
            
        });
        </script>';
    } else {
         //Otherwise, add button to pop
         echo '
         </script> 
         <p><strong><a href="javascript: doIt()">Press to pop completion box</a></strong></p>';
    }
}

echo '
<script type="text/javascript">

    $(\'[data-toggle="collapse"]\').on(\'click\', function() {
        $(this).find("span.glyphicon").toggleClass(\'glyphicon-chevron-down glyphicon-chevron-up\');
    });
    
    
    
    //function toggleChevron(e) {
    //    $(e.target)
    //        .find("span.glyphicon").toggleClass(\'glyphicon-chevron-down glyphicon-chevron-up\');
    //}
    //$(\'[data-toggle="collapse"\').on(\'hide.bs.collapse show.bs.collapse\', toggleChevron);

    
//Steves code that Leonard wanted...

// the code that does it all
$(document).ready(function () {
  var titleObjects = [];  // empty array for storing <h2> & <h4> objects
  $("#region-main h2, #region-main h4").each(function() {
     titleObjects.push( $(this) )  // add each <h2> or <h4> object to the array
     }
  );
  theBigTitleText = "";
  if (titleObjects.length != 0) { theBigTitle = titleObjects[0]; theBigTitleText = theBigTitle.text().trim() }; // if at least one object found, make theBigTitle the first one found

  var theBreadcrumbTitle = $(\'.breadcrumb li:contains("\'+ theBigTitleText +\'"):first\'); // first item in the breadcrumb trail with the same title
  
  var theTop = theBigTitle.offset().top - 25 - parseFloat(theBigTitle.css(\'marginTop\').replace(/auto/, 100)); // vertical position of theBigTitle
  var linkVisible = false; //switch
  
  // when there is a scroll event...
  $(window).scroll(function (event) {
    
    // the current scroll amount
    var yPos = $(this).scrollTop();

    // if big title goes off screen fade in breadcrumb title
    if ((yPos >= theTop) && !linkVisible) {
theBreadcrumbTitle.css(\'opacity\', \'0\').css(\'display\', \'inline-block\').animate({opacity: \'1\'}, 150);
linkVisible = true; // once faded in, don\'t do anything until the big title comes back into view
return;
    }
    // if big title comes on screen, fade out breadcrumb title
    if ((yPos < theTop) && linkVisible) {
    
theBreadcrumbTitle.animate({opacity: \'0\'}, 150, function() { theBreadcrumbTitle.hide() });
    
linkVisible = false; // once faded out, don\'t do anything until the big title goes out of view
    
return;
    }
  });
});
</script>
';

if ($showcompletionnextactivity) {
    echo '<div class="next_activity_area"><div class="next_activity_link"><div class="activity-complete"><span class="activity-complete glyphicon glyphicon-ok"></span><div class="done">Activity complete</div></div><div class="next_activity_text"> <a class="next_activity" href="' . $forwardlinkurl . '"><div class="nav_icon"><i class="icon-arrow-right"></i></div><span class="text"><span class="nav_guide">' . $forwardlinktext . '</span><br>' . $forwardlinkname . '</span></a></div></div></div>';
}



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
