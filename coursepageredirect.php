<?php

// This script http redirects the user away from the site index/dashboard based on a theme setting.

// Inclusion of this file is the earliest entry point from a theme load: (top of local.php)
// (without use of a local plugin to redirect early. Therefore it is not the most efficient, 
// because a small, but reasonable amount of work has already been done to generate the current page)

// Detail:
// It doesn't make sense, when using the snap theme, to use either of the following:
// - The moodle core site index
// - The moodle core dashboard (i.e. the 'my' page)

// This is because:
// - Students don't care about a site index. Students want to be on their course pages.
// - Under snap, students don't need a separate dashboard because of the primary nav popup menu

// We have added a setting: 'coursepageredirect' to avoid the core site index and core dashboard page
// if a relevant course can be found for the user

// The user is redirected to the most recent timestamp for: 'course access' / 'enrolment'
// on all associated courses.
   
// Do nothing for AJAX calls and CLI scripts:
if (defined('AJAX_SCRIPT') && AJAX_SCRIPT) return;
if (defined('CLI_SCRIPT') && CLI_SCRIPT) return;

global $USER;

// Defensive programming: $USER should normally be set at this inclusion point.
// If user is undetermined (null), do nothing.
if (!is_object($USER)) return;

// If the user isn't logged in, don't bother redirecting them anywhere
// The calling function is technically pretty boring, but semantically correct
// and the contents may change at some future point
if (!isloggedin()) return;

// Do nothing for guest user.
// Function parameter default is $USER. 
if (isguestuser()) return;

// Do nothing for admin user. Allow admin user to access site-index and my-index under snap.
// Function parameter default is $USER.    
if (is_siteadmin()) return;
   
// TODO: could potentially allow other users based on capabilities
// view site-index and/or my-index

// Check theme config setting for redirecting logins
// Short circuit early if setting is undefined or not enabled
if (empty($config->settings->coursepageredirect)) return;

global $PAGE;

// Short circuit validation
// Defensive programming: $PAGE should always be set at this inclusion point.
if (!is_object($PAGE)) return;

// Declare destination course id
$destinationcourseid = 0;

// Only prevent user from seeing the mydashboard and siteindex
// Short circuit early for any other page
if ($PAGE->pagetype != 'site-index' && $PAGE->pagetype != 'my-index') return;
    
// This script checks three areas for course access:
// - Courses within the current session
// - Courses within previous sessions
// - Courses the user is enrolled in

// The precedence for which course to be redirected to is solely:
// - The most recent timestamp

// Check current course access
$coursetime = 0;
if (!empty($USER->currentcourseaccess)) {
    foreach ($USER->currentcourseaccess as $currentcourseid => $currentcourseaccesstime) {
        if ($currentcourseaccesstime > $coursetime) {
            $coursetime = $currentcourseaccesstime;
            $destinationcourseid = $currentcourseid;      
        }
    }
}

// Check last course access
if (!empty($USER->lastcourseaccess)) {
    foreach ($USER->lastcourseaccess as $lastcourseid => $lastcourseaccesstime) {
        if ($lastcourseaccesstime > $coursetime) {
            $coursetime = $lastcourseaccesstime;
            $destinationcourseid = $lastcourseid;      
        }
    }
}

// Check enrolled courses.
if (!empty($USER->enrol)) {
    foreach ($USER->enrol['enrolled'] as $enrolledcourseid => $enrolledcourseaccesstime) {
        if ($enrolledcourseaccesstime > $coursetime) {
            $coursetime = $enrolledcourseaccesstime;
            $destinationcourseid = $enrolledcourseid;      
        }
    }   
}

// Check enrolled courses 'Enrolled' won't always be populated.
// Could change this to be dependant on whether $USER->enrol is populated.
$enrollments = theme_snap_enrol_get_my_courses();
if (!empty($enrollments)) {
    foreach ($enrollments as $enrollment) {
        if ($enrollment->timecreated > $coursetime) {
            $coursetime = $enrollment->timecreated;
            $destinationcourseid = $enrollment->id;
        }
    }     
}

// Short circuit early if no course is found
// (This will allow rendering of the site-index or my-index for the user because no redirect course can be determined)
if (empty($destinationcourseid)) return;

// courseid has been found
// Redirect to that course, at the current section
// Current section is determined by the course format
// (e.g. topics / weeks)

//This means we need to load the course format associated with the course

global $DB;
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/course/format/lib.php');

// Retrieve the database tuple as an associative array
$course = $DB->get_record('course', array('id' => $destinationcourseid));

// Defensive programming: short cirtcuit if preferred course cannot be found
// (e.g. course has been deleted / restored with a different id)
// (This will allow rendering of the site-index or my-index for the user because no redirect course can be determined)
if (!$course) return;

// Retrieve the course format for the course
$format = course_get_format($course);

// Retrieve the "format-qualified" course object
$course = $format->get_course();
$modinfo = get_fast_modinfo($course);

// Loop through all sections in course   
foreach ($modinfo->get_section_info_all() as $section => $thissection) {
    
    // Defensive. Short circuit for 'Phantom' sections.
    if ($section > $course->numsections) {
        continue;
    }

    // Check if this section is the current section for the course / course format
    if (course_get_format($course)->is_section_current($section)) {
        
        // Generate a moodle url for redirect
        $url = new moodle_url('/course/view.php', array('id' => $destinationcourseid), 'section-' . $section);
        redirect($url);
                    
        // Defensive programming: redirect makes a call to exit() so this return is redundant.
        return; 
    }
}

// If no sections are current, simply redirect to the course page
// Generate a moodle url for redirect
$url = new moodle_url('/course/view.php', array('id' => $destinationcourseid));
redirect($url);

// Defensive programming: redirect makes a call to exit() so this return is redundant.
return;

// Function stolen and simplified/repurposed from enrollib.php

// This function needs to return an associative array of objects keyed on the courseid
// and containing the time the user enrolment was created
// core: enrol_get_my_courses doesn't return the {user_enrolment}.timecreated by default.
// A facility exists on core enrol_get_my_courses to add fields to the select query.
// Unfortunately, the fields are prepended with 'c.': the course table alias.

// A left join to the context table has been copied blindly from enrollib.php
// I had assumed a context would always exist at this point, but I am keeping the 
// left join due to "Chesterton's fence"
// In any case, preload_from_record from accesslib.php will create the context if
// it doesn't exist.

// There is also no attempt in this core function to limit a repeated course enrolment 
// for the same user and course to different enrolment methods, so it isn't neccessary
// to use a DISTINCT subquery, and in this use case it is more appropriate to retain 
// this flattened data anyway. (Two timestamps will exist for the course user enrolment)

function theme_snap_enrol_get_my_courses() {
    global $DB, $USER;
    
    $params = array();
    $conditions = array();

    //SELECT fields
    $coursefields = array('id', 'visible');
        
    $userenrolmentsfields = array('timecreated');
    
    $coursecontextfields = context_helper::get_preload_record_columns_sql('ctx');
    
    //WHERE fields
    $conditions[] = "ue.userid = :userid";
    $params['userid'] = $USER->id;
                        
    $conditions[] = "c.id <> :siteid";
    $params['siteid'] = SITEID;
    
    $conditions[] = "ue.status = :active";
    $params['active'] = ENROL_USER_ACTIVE;
    
    $conditions[] = "e.status = :enabled";
    $params['enabled'] = ENROL_INSTANCE_ENABLED;
    
    $conditions[] = "ue.timestart < :now1";
    $params['now1'] = round(time(), -2); // improves db caching
    
    $conditions[] = "(ue.timeend = 0 OR ue.timeend > :now2)";
    $params['now2'] = $params['now1'];   

    // Hacky: enrollib.php purposefully restricts enroled courses to only contexts of the loginascontext
    // Seems reasonable enough to me?... 'Login As' never works properly anyway...
    if (isset($USER->loginascontext) and $USER->loginascontext->contextlevel == CONTEXT_COURSE) {
        $conditions[] = "courseid = :loginas";
        $params['loginas'] = $USER->loginascontext->instanceid;
    }
   
    //convert field list into string
    $coursefields = 'c.' . join(", c.", $coursefields);
    $userenrolmentsfields = 'ue.' . join(", ue.", $userenrolmentsfields);    
    
    //convert condition list into string
    $conditions = join(" AND ", $conditions);

    
    $usercourseenrollment = "
            SELECT $coursefields, $userenrolmentsfields, $coursecontextfields
            FROM {course} c
            INNER JOIN {enrol} e ON c.id = e.courseid
            INNER JOIN {user_enrolments} ue ON ue.enrolid = e.id
            LEFT JOIN {context} ctx ON (ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel)
            WHERE $conditions";

    $params['contextlevel'] = CONTEXT_COURSE;      
    
    $courses = $DB->get_records_sql($usercourseenrollment, $params);

    // preload contexts and check visibility
    // This loop features: 'interesting' use of 'unset'
    // It's weird. Really weird. Would you write it like this from scratch?
    foreach ($courses as $id => $course) {
        
        // For records with non null joins to context, this function will short circuit
        // Otherwise the context elements of the $course object will be added to cache,
        // and removed from the original object, then the return value is ignored.
        context_helper::preload_from_record($course);
        
        // If the course is invisible, maybe the user is allowed to see invisible courses?
        if (!$course->visible) {
            
            // Attempt to pull the context from cache. (Probably added three lines above)
            // If not present in cache then a context record is created and returned (and
            // declared locally but never used locally).
            // The only reason this function would return false would be if 'instance'
            // failed to create a context record. This can probably never happen and the 
            // code coverage of this statement block is nil for all tests.
            // Why is this category only checked for invisible courses?
            // TODO: How can this be neccessary?
            // I suspect this may be defensive or in error.
            if (!$context = context_course::instance($id, IGNORE_MISSING)) {
                unset($courses[$id]);
                continue;
            }
            
            // If the user in the current context can not view hidden courses,
            // short circuit skip to the next enrolled course
            if (!has_capability('moodle/course:viewhiddencourses', $context)) {
                unset($courses[$id]);
                continue;
            }
        }
        
        // Re-add the course object to the array. The only possible motive for this
        // is because the course object is mutated in the preload_from_record method above
        // but the only way the object is mutated is by unsetting the context members
        // if they exist. This is dumb.
        $courses[$id] = $course;
    }

    return $courses;
}