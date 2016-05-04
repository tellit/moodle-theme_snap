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
// Functions for all formats....
/**
 * Shoehorn theme.
 *
 * @package    theme
 * @subpackage shoehorn
 * @copyright  &copy; 2015-onwards G J Barnard in respect to modifications of the Bootstrap theme.
 * @author     G J Barnard - gjbarnard at gmail dot com and {@link http://moodle.org/user/profile.php?id=442195}
 * @author     Based on code originally written by Bas Brands, David Scotson and many other contributors.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 //namespace theme_shoehorn;
trait format_renderer_toolbox {
    protected function get_nav_links($course, $sections, $sectionno) {
        return array();
    }
    /**
     * Generate the display of the header part of a section before
     * course modules are included
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @param stdClass $format format_XXXXX instance.
     * @param int $displaysection The section number in the course which is being displayed
     * @return string HTML to output.
     */
    public function course_format_section_header($section, $course, $format, $displaysection) {
        $o = '';
        $sectionstyle = '';
        if ($section->section != 0) {
            // Only in the non-general sections.
            if (!$section->visible) {
                $sectionstyle = ' hidden';
            } else if ($format->is_section_current($section)) {
                $sectionstyle = ' current';
            }
        }
        if ($section->section == $displaysection) {
            $sectionstyle .= ' active';
        }
        $o .= \html_writer::start_tag('li', array(
            'id' => 'section-' . $section->section,
            'class' => 'section main clearfix' . $sectionstyle . ' item', 'role' => 'region',
            'aria-label' => $format->get_section_name($section)));
        $leftcontent = $this->section_left_content($section, $course, true);
        $o .= \html_writer::tag('div', $leftcontent, array('class' => 'left side'));
        $rightcontent = $this->section_right_content($section, $course, true);
        $o .= \html_writer::tag('div', $rightcontent, array('class' => 'right side'));
        $o .= \html_writer::start_tag('div', array('class' => 'content'));
        $o .= \html_writer::tag('h3', $format->get_section_name($section), array('class' => 'sectionname'));
        $o .= \html_writer::start_tag('div', array('class' => 'summary'));
        $o .= $this->format_summary_text($section);
        $context = \context_course::instance($course->id);
        $o .= \html_writer::end_tag('div');
        $o .= $this->section_availability_message($section, has_capability('moodle/course:viewhiddensections', $context));
        return $o;
    }
    /**
     * Generate the display of the footer part of a section
     *
     * @return string HTML to output.
     */
    public function course_format_section_footer() {
        $o = \html_writer::end_tag('div');
        $o .= \html_writer::end_tag('li');
        return $o;
    }
    /**
     * Output the html for a single section page.
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections (argument not used)
     * @param array $mods (argument not used)
     * @param array $modnames (argument not used)
     * @param array $modnamesused (argument not used)
     * @param int $displaysection The section number in the course which is being displayed
     */
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE;
        if ($PAGE->user_is_editing()) {
            echo \html_writer::start_tag('div', array('class' => 'panel panel-default'));
            echo \html_writer::tag('h3', get_string('editonmainpage', 'theme_shoehorn'));
            echo \html_writer::end_tag('div');
            return;
        }
        $modinfo = get_fast_modinfo($course);
        $format = course_get_format($course);
        $course = $format->get_course();
        // Can we view the section in question?
        if (!($sectioninfo = $modinfo->get_section_info($displaysection))) {
            // This section doesn't exist.
            print_error('unknowncoursesection', 'error', null, $course->fullname);
            return;
        }
        if (!$sectioninfo->uservisible) {
            if (!$course->hiddensections) {
                echo $this->start_section_list();
                echo $this->section_hidden($displaysection);
                echo $this->end_section_list();
            }
            // Can't view this section.
            return;
        }
        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);
        // Start single-section div.
        echo \html_writer::start_tag('div', array('class' => 'shoehorn-single-course-page'));
        $sections = $modinfo->get_section_info_all();
        // Check we will have a section to show...
        $shownsections = array();
        foreach ($sections as $thissection) {
            if ($thissection->section > $course->numsections) {
                /* Activities inside this section are 'orphaned', this section will be printed on the main
                   course page when editing is on. */
                break; // Not sure why core does not use this instead of 'continue'?
            }
            $showsection = $thissection->uservisible ||
                ($thissection->visible && !$thissection->available && $thissection->showavailability &&
                !empty($thissection->availableinfo));
            if ($showsection) {
                $shownsections[] = $thissection->section;
            }
        }
        if (count($shownsections) > 0) {
            $loopsection = 0;
            $numsections = count($shownsections);
            echo \html_writer::start_tag('div', array('class' => 'carouselslider'));
            echo \html_writer::start_tag('div', array(
                'id' => 'myCourseCarousel', 'class' => 'carousel slide',
                'data-ride' => 'carousel', 'data-interval' => ''));
            $pips = \html_writer::start_tag('ol', array('class' => 'carousel-indicators'));
            $slides = \html_writer::start_tag('ul', array('class' => 'topics carousel-inner'));
            $loopsection = 0;
            $slidecount = 0;
            while ($loopsection < $numsections) {
                $thissection = $sections[$shownsections[$loopsection]];
                $loopsection++;
                $showpip = false;
                if ($thissection->section == 0) {
                    // 0-section is displayed a little different than the others.
                    if (get_class($format) != 'format_noticebd') {
                        if ($thissection->summary or !empty($modinfo->sections[0])) {
                            $slides .= $this->course_format_section_header($thissection, $course, $format, $displaysection);
                            $slides .= $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                            $slides .= $this->courserenderer->course_section_add_cm_control($course, 0, 0);
                            $slides .= $this->course_format_section_footer();
                            $showpip = true;
                        }
                    } else {
                        echo $this->course_format_section_header($thissection, $course, $format, $displaysection);
                        // See if we are using a version of the format's renderer where the method 'print_noticeboard' is public.
                        if (in_array('print_noticeboard', get_class_methods($this))) {
                            $this->print_noticeboard($course);
                            if (($PAGE->user_is_editing()) && (is_siteadmin($USER))) {
                                $slides .= $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
                                $slides .= $this->courserenderer->course_section_add_cm_control($course, $displaysection,
                                    $displaysection);
                            }
                        } else {
                            $slides .= $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                            $slides .= $this->courserenderer->course_section_add_cm_control($course, 0, 0);
                        }
                        $slides .= $this->course_format_section_footer();
                        $showpip = true;
                    }
                } else {
                    $slides .= $this->course_format_section_header($thissection, $course, $format, $displaysection);
                    if ($thissection->uservisible) {
                        $slides .= $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                        $slides .= $this->courserenderer->course_section_add_cm_control($course, $thissection->section, 0);
                    }
                    $slides .= $this->course_format_section_footer();
                    $showpip = true;
                }
                if ($showpip) {
                    $attributes = array('data-target' => '#myCourseCarousel', 'data-slide-to' => $slidecount,
                        'title' => $format->get_section_name($thissection->section));
                    if ($thissection->section == $displaysection) {
                        $attributes['class'] = 'active';
                    }
                    $pips .= \html_writer::start_tag('li', $attributes);
                    $pips .= \html_writer::end_tag('li');
                    $slidecount++;
                }
            }
            $pips .= \html_writer::end_tag('ol');
            $slides .= \html_writer::end_tag('ul');
            echo $pips;
            echo $slides;
            echo \html_writer::start_tag('a', array('class' => 'left carousel-control', 'href' => '#myCourseCarousel',
                'data-slide' => 'prev'));
            $fontawesome = toolbox::get_setting('fontawesome');
            if ($fontawesome) {
                echo \html_writer::tag('i', '', array('class' => 'fa fa-chevron-circle-left'));
            } else {
                echo \html_writer::tag('span', '', array('class' => 'glyphicon glyphicon-chevron-left'));
            }
            echo \html_writer::end_tag('a');
            echo \html_writer::start_tag('a', array('class' => 'right carousel-control', 'href' => '#myCourseCarousel',
                'data-slide' => 'next'));
            if ($fontawesome) {
                echo \html_writer::tag('i', '', array('class' => 'fa fa-chevron-circle-right'));
            } else {
                echo \html_writer::tag('span', '', array('class' => 'glyphicon glyphicon-chevron-right'));
            }
            echo \html_writer::end_tag('a');
            echo \html_writer::end_tag('div');
            echo \html_writer::end_tag('div');
        } else {
            echo \html_writer::start_tag('div', array('class' => 'panel panel-default'));
            echo \html_writer::tag('h3', get_string('nosectionstoshow', 'theme_shoehorn'));
            echo \html_writer::end_tag('div');
        }
        // Close single-section div.
        echo \html_writer::end_tag('div');
    }
}