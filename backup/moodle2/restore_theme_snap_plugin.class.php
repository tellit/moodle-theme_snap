<?php
  class restore_theme_snap_plugin extends restore_theme_plugin {

    /**
     * Returns the paths to be handled by the plugin at course level
     */
    protected function define_course_plugin_structure() {
        $paths = array();

        // Because of using get_recommended_name() it is able to find the
        // correct path just by using the part inside the element name (which
        // only has a /snap element).
        $elepath = $this->get_pathfor('/snap');

        // The 'snap' here defines that it will use the process_snap function
        // to restore its element.
        $paths[] = new restore_path_element('snap', $elepath);

        return $paths;
    }

    /**
     * Called after this runs for a course.
     */
    function after_execute_course() {
        // Need to restore file
        $this->add_related_files('theme_snap', 'image', null);
    }

    /**
     * Process the 'snap' element
     */
    public function process_snap($data) {
        global $DB;

        // Get data record ready to insert in database
        $data = (object)$data;
        $data->courseid = $this->task->get_courseid();

        // See if there is an existing record for this course
        $existingid = $DB->get_field('theme_snap_courseoptions', 'id',
                array('courseid'=>$data->courseid));
        if ($existingid) {
            $data->id = $existingid;
            $DB->update_record('theme_snap_courseoptions', data);
        } else {
            $DB->insert_record('theme_snap_courseoptions', $data);
        }

        // No need to record the old/new id as nothing ever refers to
        // the id of this table.
    }
}
?>
