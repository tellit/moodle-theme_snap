<?php
 class backup_theme_snap_plugin extends backup_theme_plugin {

    /**
     * Returns the theme information to attach to course element
     */
    protected function define_course_plugin_structure() {
        // Define virtual plugin element
        $plugin = $this->get_plugin_element(null, $this->get_theme_condition(), 'snap');

        // Create plugin container element with standard name
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // Add wrapper to plugin
        $plugin->add_child($pluginwrapper);

        // Set up theme's own structure and add to wrapper
        $snap = new backup_nested_element('snap', array('id'), array('variant'));
        
        $pluginwrapper->add_child($snap);

        // Use database to get source
        $snap->set_source_table('theme_snap_courseoptions',
                array('courseid' => backup::VAR_COURSEID));

        // Include files which have theme_ou and area image and no itemid
        $snap->annotate_files('theme_snap', 'image', null);

        return $plugin;
    }
} 

/*

<plugin_theme_snap_course>
 <snap id="2">
  <variant>purple</variant>
 </snap>
</plugin_theme_snap_course>

*/
?>



