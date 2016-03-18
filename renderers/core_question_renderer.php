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
 * Snap course renderer.
 * Overrides core course renderer.
 *
 * @package   theme_snap
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/question/engine/renderer.php");
class theme_snap_core_question_renderer extends core_question_renderer {
    
    /**
     * Generate the information bit of the question display that contains the
     * metadata like the question number, current state, and mark.
     * @param question_attempt $qa the question attempt to display.
     * @param qbehaviour_renderer $behaviouroutput the renderer to output the behaviour
     *      specific parts.
     * @param qtype_renderer $qtoutput the renderer to output the question type
     *      specific parts.
     * @param question_display_options $options controls what should and should not be displayed.
     * @param string|null $number The question number to display. 'i' is a special
     *      value that gets displayed as Information. Null means no number is displayed.
     * @return HTML fragment.
     */
    protected function info(question_attempt $qa, qbehaviour_renderer $behaviouroutput,
            qtype_renderer $qtoutput, question_display_options $options, $number) {
        $output = '';      
        if (!empty($this->page->theme->settings->questionsemanticactivation)) {
            $output .= $this->semanticactivation($qa);
        } else {
            $output .= $this->number($number);
        }
        $output .= $this->status($qa, $behaviouroutput, $options);
        $output .= $this->mark_summary($qa, $behaviouroutput, $options);
        $output .= $this->question_flag($qa, $options->flags);
        $output .= $this->edit_question_link($qa, $options);
        return $output;
    }
    
    /**
     * Generate a language string title text based on the question type
     * 
     * @param question_attempt $qa the question attempt to display.
     * @return HTML fragment.
     */
    protected function semanticactivation(question_attempt $qa) {
        $languagestring = 'questionsemanticactivation-' . $qa->get_question()->qtype->name();
        $title =  get_string($languagestring, 'theme_snap');
        
        if (empty($title)) $title = $questiontype . ' is an unsupported question type';
        return html_writer::tag('h3', $title, array('class' => 'qtype'));
    }
}
