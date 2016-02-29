<?php
    global $PAGE;
    if (!empty($config->settings->hidecoursepage)) {
        //Inclusion of this file is the earliest point in a course view page load where the course page load can be hijacked
        //(without use of a local plugin to redirect early)
        //var_dump($PAGE);
        //$urltogo = new moodle_url('/course/view.php', array('id' => $PAGE->course->id));
        
         //'_pagetype' => string 'my-index' (length=8)
         //   protected '_pagelayout' => string 'mydashboard' (length=11)
         //'my-index' 'course-view' 'site-index'
        //if ($PAGE->pagetype == 'course-view')     
        //TODO:
        //If current $PAGE is the course page
        
        //Get course completion information
        //Figure out next mod to redirect to
        //redirect here
        //$courseid = required_param('courseid', PARAM_INT);
        //$url = new moodle_url('/course/view.php', array('id' => $courseid));
        //redirect($url);
    }
?>
