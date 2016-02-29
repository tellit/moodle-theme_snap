<?php
    global $PAGE;
    var_dump($PAGE);
    if (!empty($this->page->theme->settings->hidecoursepage)) {
        //Inclusion of this file is the earliest point in a course view page load where the course page load can be hijacked
        //(without use of a local plugin to redirect early)
        global $PAGE;
        var_dump($PAGE);
    }
?>
