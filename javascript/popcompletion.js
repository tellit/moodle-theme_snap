M.theme_snap = M.theme_snap || {
    courseid : false
};
M.theme_snap.core = {
    init: function(Y, courseid, contextid, courseconfig, settings, mod) {
        // Add courseid to moodle cfg variable (this is here for future proofing in case we need it)
        M.theme_snap.courseid = courseid;
        M.theme_snap.courseconfig = courseconfig;
        M.theme_snap.settings = settings;
        M.theme_snap.mod = mod;
        M.cfg.context = contextid;
        $(document).ready(snapInit);
    },
    
    addPopCompletion: function (Y) {
        if (typeof M.theme_snap.settings.nextactivitymodaldialogdelay != 'undefined') {
            var manualPopActivities = ['page', 'book', 'wiki'];
            if (manualPopActivities.indexOf(M.theme_snap.mod.modname) == -1) {

                //Using bootstrap modal
                //setTimeout(function(){$(\'#activitycompletemodal\').modal(\'show\');}, ' . $this->page->theme->settings->nextactivitymodaldialogdelay . ');
                          
                //using animate slide position fixed
                setTimeout(
                    function() {
                        popCompletion();
                    },
                    
                    // This is currently  populated on module.js init, but could be passed directly 
                    // to this function as a local parameter via js_init_call
                    M.theme_snap.settings.nextactivitymodaldialogdelay
                );
            }
        }
    }
    
};